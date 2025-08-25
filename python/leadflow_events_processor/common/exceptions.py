"""
Provides common exceptions classes.
"""


class RecordNotFoundError(Exception):
    """
    Exception raised when a record is not found in the database.
    """

    def __init__(self, details: str = ''):
        """
        Initialize default message of record not found.
        """
        if details:
            super().__init__(f'No record found, {details}.')
        else:
            super().__init__('No record found.')
