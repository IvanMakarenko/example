"""
Provides events processor implementation.
"""

import logging
from threading import Event as TerminationEvent
from typing import (
    Any,
    Optional,
)

from pydantic import ValidationError

from leadflow_events_processor.common.exceptions import RecordNotFoundError
from leadflow_events_processor.entities.event import Event
from leadflow_events_processor.external_services.kafka.consumer.json import BaseJsonMessageConsumer
from leadflow_events_processor.external_services.kafka.message.json import DeserializedJsonMessage
from leadflow_events_processor.external_services.tidb.client import TidbClient
from leadflow_events_processor.services.synchronize.factory import SyncFactory


class EventsProcessor(BaseJsonMessageConsumer):
    """
    Events processor implementation.
    """

    def __init__(
        self,
        config: dict[str, Any],
        topics: list[str],
        tidb_client: TidbClient,
        batch_size: int = 1,
        batch_timeout: float = -1,
        termination_event: Optional[TerminationEvent] = None,
        **kwargs: Any,
    ):
        """
        Initialize the object.

        Args:
            config: Kafka consumer configuration parameters.
            topics: list of topics to subscribe.
            tidb_client: TiDB client to execute database queries.
            batch_size: the maximum number of messages to consume from Kafka at a time.
            batch_timeout: the maximum time to block waiting for messages. Default: `-1` (infinite).
            termination_event: event to stop consuming loop.
            source: default source name used by the current consumer service.
            kwargs: extra keyword arguments.
        """
        super().__init__(
            config=config,
            topics=topics,
            batch_size=batch_size,
            batch_timeout=batch_timeout,
            termination_event=termination_event,
            **kwargs,
        )
        self._tidb_client = tidb_client

    def process_messages(self, messages: list[DeserializedJsonMessage]) -> None:  # type: ignore[override]
        """
        Process batch of deserialized messages consumed from Kafka.

        Args:
            messages: list of deserialized event messages.
        """
        if not messages:
            return

        logging.debug(f'Received messages count: {len(messages)}')

        for message in messages:
            try:
                event = Event.model_validate(message.value)
                factory = SyncFactory(event_type=event.type)
                syncronizator = factory.get_sync(data=event.data)
                syncronizator.sync(tidb_client=self._tidb_client)
            except (ValidationError, RecordNotFoundError) as exception:
                logging.error(f'Skip event; Key: {message.key}; Value: {message.value}; Exception: {exception}')

        logging.debug('-' * 20)
