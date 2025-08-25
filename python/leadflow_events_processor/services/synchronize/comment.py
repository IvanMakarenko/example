"""
Provides events with Comment entity for synchronize.
"""

from leadflow_events_processor.common.enums import SourceEstatesField
from leadflow_events_processor.services.synchronize.behaviours import (
    AddCompanyBehaviour,
    RemoveCompanyBehaviour,
)


class CommentAdd(AddCompanyBehaviour):
    """
    Handle add new comment event.
    """

    @property
    def field_name(self) -> SourceEstatesField:
        """
        Return related field for syncronize record in TiDB.
        """
        return SourceEstatesField.APP_COMMENTED


class CommentRemove(RemoveCompanyBehaviour):
    """
    Handle remove comment event.
    """

    @property
    def field_name(self) -> SourceEstatesField:
        """
        Return related field for syncronize record in TiDB.
        """
        return SourceEstatesField.APP_COMMENTED
