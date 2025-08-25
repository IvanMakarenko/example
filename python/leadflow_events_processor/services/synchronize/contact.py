"""
Provides events with Contact entity for synchronize.
"""

from leadflow_events_processor.common.enums import SourceEstatesField
from leadflow_events_processor.services.synchronize.behaviours import (
    AddCompanyBehaviour,
    RemoveCompanyBehaviour,
)


class ContactAdd(AddCompanyBehaviour):
    """
    Handle add new contact event.
    """

    @property
    def field_name(self) -> SourceEstatesField:
        """
        Return related field for syncronize record in TiDB.
        """
        return SourceEstatesField.APP_CONTACTED


class ContactRemove(RemoveCompanyBehaviour):
    """
    Handle remove contact event.
    """

    @property
    def field_name(self) -> SourceEstatesField:
        """
        Return related field for syncronize record in TiDB.
        """
        return SourceEstatesField.APP_CONTACTED
