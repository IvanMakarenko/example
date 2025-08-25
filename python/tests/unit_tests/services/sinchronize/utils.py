"""
Provide utils for simplify code of tests.
"""

from typing import Any
from unittest.mock import MagicMock, patch

from leadflow_events_processor.external_services.tidb.repositories import SourceEstatesRepository
from leadflow_events_processor.services.synchronize.base import BaseSync


def sync_with_mocks(service: BaseSync, start_value: Any, result_value: Any):
    """
    Test sync function with mocks for repository (TiDB).
    """
    with patch.object(SourceEstatesRepository, f'get_{service.field_name}', return_value=start_value):
        with patch.object(SourceEstatesRepository, f'update_{service.field_name}') as mock_update:
            service.sync(tidb_client=MagicMock())
            if start_value == result_value:
                mock_update.assert_not_called()
            else:
                mock_update.assert_called_once_with(listing_id=service.data.listing_id, company_ids=result_value)
