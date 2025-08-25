"""
Provides unit tests for the events processor service.
"""

import time
from threading import Thread

from leadflow_events_processor.common.signal_handlers import sigterm_handler
from leadflow_events_processor.services.events_processor.processor import EventsProcessor


def test_run(events_processor: EventsProcessor):
    """
    Case: Check the events processor can be running and stopped by the SIGTERM signal.
    Expect: Events processor can be running and stopped.
    """

    def kafka_consumer_consume_side_effect(*args, **kwargs):
        time.sleep(0.15)
        return []

    events_processor._consumer.consume.side_effect = kafka_consumer_consume_side_effect
    events_processor_tread = Thread(target=events_processor.run)
    events_processor_tread.start()

    time.sleep(0.25)
    sigterm_handler()
    events_processor_tread.join()

    events_processor._consumer.subscribe.assert_called_once()
    events_processor._consumer.consume.assert_called()
