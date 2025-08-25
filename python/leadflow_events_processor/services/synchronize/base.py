"""
Syncronize interface.
"""

from abc import (
    ABC,
    abstractmethod,
)
from typing import Any

from leadflow_events_processor.common.enums import SourceEstatesField
from leadflow_events_processor.entities.event import EventData
from leadflow_events_processor.external_services.tidb.client import TidbClient
from leadflow_events_processor.external_services.tidb.repositories import SourceEstatesRepository


class BaseSync(ABC):
    """
    Base Syncronize realization.
    """

    def __init__(self, data: EventData):
        """
        Initialize data for syncronize.
        """
        self.data = data

    @property
    @abstractmethod
    def field_name(self) -> SourceEstatesField:
        """
        Return related field for syncronize record in TiDB.
        """

    @abstractmethod
    def sync_value(self, previous_value: Any) -> Any:
        """
        Synchronize previous value of record in TiDB to new.
        """

    def sync(self, tidb_client: TidbClient) -> None:
        """
        Synchronize field in TiDB if value has been changed.
        """
        repository = SourceEstatesRepository(tidb_client=tidb_client)
        method_get = f'get_{self.field_name}'
        old_value = getattr(repository, method_get)(listing_id=self.data.listing_id)
        new_value = self.sync_value(previous_value=old_value)

        if old_value != new_value:
            method_update = f'update_{self.field_name}'
            getattr(repository, method_update)(listing_id=self.data.listing_id, company_ids=new_value)

        current = getattr(repository, method_get)(listing_id=self.data.listing_id)
        print('current', current)
