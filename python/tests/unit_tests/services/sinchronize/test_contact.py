"""
Provides unit tests for the contact events.
"""

from leadflow_events_processor.services.synchronize.contact import ContactAdd, ContactRemove

from .utils import sync_with_mocks


def test_add_event_to_empty(event_data):
    """
    Case: start value is empty list.
    Expect: result value is list with company id.
    """
    service = ContactAdd(data=event_data)
    start_value = []

    result_value = service.sync_value(previous_value=start_value)
    assert 1 == len(result_value)
    assert event_data.company_id in result_value

    sync_with_mocks(service=service, start_value=start_value, result_value=result_value)


def test_add_event_to_other_list(event_data):
    """
    Case: start value is list with other value.
    Expect: result value is list with company id and start value.
    """
    service = ContactAdd(data=event_data)
    start_value = ['other_test_company']

    result_value = service.sync_value(previous_value=start_value)
    assert 2 == len(result_value)
    assert event_data.company_id in result_value
    assert set(start_value).issubset(result_value)

    sync_with_mocks(service=service, start_value=start_value, result_value=result_value)


def test_add_event_to_same_list(event_data):
    """
    Case: start value is list with company id.
    Expect: result value is the same as start, without changes.
    """
    service = ContactAdd(data=event_data)
    start_value = [event_data.company_id]

    result_value = service.sync_value(previous_value=start_value)
    assert len(start_value) == len(result_value)
    assert start_value == result_value

    sync_with_mocks(service=service, start_value=start_value, result_value=result_value)


def test_remove_event_from_empty(event_data):
    """
    Case: start value is empty list.
    Expect: result value as start, skip event.
    """
    service = ContactRemove(data=event_data)
    start_value = []

    result_value = service.sync_value(previous_value=start_value)
    assert 0 == len(result_value)
    assert start_value == result_value

    sync_with_mocks(service=service, start_value=start_value, result_value=result_value)


def test_remove_event_from_other_list(event_data):
    """
    Case: start value is list with other value.
    Expect: result value as start, skip event.
    """
    service = ContactRemove(data=event_data)
    start_value = ['other_test_company']

    result_value = service.sync_value(previous_value=start_value)
    assert len(start_value) == len(result_value)
    assert start_value == result_value

    sync_with_mocks(service=service, start_value=start_value, result_value=result_value)


def test_remove_event_from_list_with_same_company(event_data):
    """
    Case: start value is list with company id.
    Expect: result value is subset of start value, but without company id.
    """
    service = ContactRemove(data=event_data)
    start_value = [event_data.company_id, 'other_test_company']

    result_value = service.sync_value(previous_value=start_value)
    assert len(start_value) > len(result_value)
    assert set(result_value).issubset(start_value)
    assert event_data.company_id not in result_value

    sync_with_mocks(service=service, start_value=start_value, result_value=result_value)
