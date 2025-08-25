"""
Provides factory of synchronizations by type.
"""

from leadflow_events_processor.common.enums import EventType
from leadflow_events_processor.entities.event import EventData
from leadflow_events_processor.services.synchronize.base import BaseSync
from leadflow_events_processor.services.synchronize.comment import (
    CommentAdd,
    CommentRemove,
)
from leadflow_events_processor.services.synchronize.contact import (
    ContactAdd,
    ContactRemove,
)


class SyncFactory:
    """
    Factory for creating syncronize instances based on event type.
    """

    def __init__(self, event_type: EventType):
        """
        Initialize the factory with a specific event type.
        """
        self.event_type = event_type

    def get_sync(self, data: EventData) -> BaseSync:
        """
        Create an synchronize instance based on the given data.

        Raises:
            NotImplementedError: If the event type does not have a corresponding
            handler class.
        """
        match self.event_type:
            case EventType.COMMENT_ADD:
                return CommentAdd(data=data)
            case EventType.COMMENT_REMOVE:
                return CommentRemove(data=data)
            case EventType.CONTACT_ADD:
                return ContactAdd(data=data)
            case EventType.CONTACT_REMOVE:
                return ContactRemove(data=data)
            case _:
                raise NotImplementedError(f'Handler for event type `{self.event_type}` is not implemented.')
