"""
Provides fixtures for the events processor service tests.
"""

from unittest.mock import (
    MagicMock,
    patch,
)

import pytest

from leadflow_events_processor.common.signal_handlers import TERMINATION_EVENT
from leadflow_events_processor.services.events_processor.processor import EventsProcessor
from leadflow_events_processor.services.events_processor.settings import events_processor_settings


@pytest.fixture(scope='package')
def events_processor() -> EventsProcessor:
    """
    Create EventsProcessor instance fixture.

    Returns:
        EventsProcessor instance.
    """

    @patch('leadflow_events_processor.external_services.kafka.consumer.base.AdminClient', MagicMock())
    @patch('leadflow_events_processor.external_services.kafka.consumer.base.Consumer', MagicMock())
    def _make_events_processor() -> EventsProcessor:
        events_processor = EventsProcessor(
            config=events_processor_settings.kafka_consumer_config.model_dump(by_alias=True),
            topics=[events_processor_settings.kafka_topic],
            batch_size=events_processor_settings.batch_size,
            batch_timeout=events_processor_settings.batch_timeout,
            termination_event=TERMINATION_EVENT,
            tidb_client=MagicMock(),
        )
        return events_processor

    return _make_events_processor()
