"""
Provide general fixtures used across tests.
"""

import pytest

from leadflow_events_processor.entities.event import EventData


@pytest.fixture
def event_json() -> dict:
    """
    Preset of example event data from application event.
    """
    return {
        'id': 12345,
        'userId': 'test_user_12345',
        'companyId': 'test_company_12345',
        'listingId': 88888,
    }


@pytest.fixture
def event_data(event_json) -> EventData:
    """
    Preset of example event data entity.
    """
    return EventData(**event_json)
