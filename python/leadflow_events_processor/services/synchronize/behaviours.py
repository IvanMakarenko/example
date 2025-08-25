"""
Syncronization bahaviours.
"""

from leadflow_events_processor.services.synchronize.base import BaseSync


class AddCompanyBehaviour(BaseSync):
    """
    Base realization of add `company_id` to value.
    """

    def sync_value(self, previous_value: list[str]) -> list[str]:
        """
        Add `company_id` to value if it's not presented.
        """
        new_value = previous_value.copy()
        if self.data.company_id not in new_value:
            new_value.append(self.data.company_id)
        return new_value


class RemoveCompanyBehaviour(BaseSync):
    """
    Base realization of remove `company_id` from value.
    """

    def sync_value(self, previous_value: list[str]) -> list[str]:
        """
        Remove `company_id` from value if it's presented.
        """
        new_value = previous_value.copy()
        if self.data.company_id in new_value:
            new_value.remove(self.data.company_id)
        return new_value
