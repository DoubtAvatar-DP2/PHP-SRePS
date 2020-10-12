<?php

    /*
    * Exception handler for database update failed.
    */
    class DatabaseUpdateFailedException extends Exception {};
    class SalesRecordUpdateFailedException extends DatabaseUpdateFailedException {};
    class RecordDetailsUpdateFailedException extends DatabaseUpdateFailedException {};
    
    /* 
    * Exception handler for missing data from UI.
    */
    class MissingDataException extends Exception {};
    class MissingRecordDataException extends MissingDataException {};
    class MissingDetailDataException extends MissingDataException {};

?>