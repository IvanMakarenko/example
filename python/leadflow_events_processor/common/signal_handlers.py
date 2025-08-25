"""
Provide common signal handlers.
"""

import logging
from threading import Event


TERMINATION_EVENT = Event()


def sigterm_handler(*args):
    """
    Handle SIGTERM signal.
    """
    logging.warning('SIGTERM signal has been received.')
    TERMINATION_EVENT.set()
