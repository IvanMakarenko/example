"""
Provides events processor service entry point.
"""

import logging
import signal

from confluent_kafka.admin import AdminClient
from sentry_sdk import capture_exception

from leadflow_events_processor.common.logging import configure_logging
from leadflow_events_processor.common.sentry import init_sentry
from leadflow_events_processor.common.signal_handlers import (
    TERMINATION_EVENT,
    sigterm_handler,
)
from leadflow_events_processor.external_services.kafka.utils import create_topic
from leadflow_events_processor.external_services.tidb.client import TidbClient
from leadflow_events_processor.services.events_processor.processor import EventsProcessor
from leadflow_events_processor.services.events_processor.settings import events_processor_settings
from leadflow_events_processor.settings import base_settings


def main():
    """
    Run service.
    """
    signal.signal(signal.SIGTERM, sigterm_handler)
    init_sentry(sentry_settings=base_settings.sentry)

    configure_logging(log_level=base_settings.log_level)
    logging.warning('Service is running...')

    tidb_client = TidbClient(url=str(base_settings.tidb.url))
    kafka_admin_client = AdminClient({'bootstrap.servers': base_settings.kafka.bootstrap_servers})

    create_topic(
        admin_client=kafka_admin_client,
        topic=events_processor_settings.kafka_topic,
        config=events_processor_settings.kafka_topic_config.model_dump(by_alias=True),
    )

    events_processor = EventsProcessor(
        config=events_processor_settings.kafka_consumer_config.model_dump(by_alias=True),
        topics=[events_processor_settings.kafka_topic],
        batch_size=events_processor_settings.batch_size,
        batch_timeout=events_processor_settings.batch_timeout,
        termination_event=TERMINATION_EVENT,
        tidb_client=tidb_client,
    )

    try:
        events_processor.run()

    except Exception as exception:
        capture_exception(exception)
        raise exception

    finally:
        tidb_client.close_connections()


if __name__ == '__main__':
    main()
