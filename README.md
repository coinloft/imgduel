# IMGDUEL #
## Foundation Classes ##
*   _**Config**_
    *    Configuration class.  This class serves as a convenient means to access configuration data within the application.
*   _**Registry**_
    *    The registry provides the application with a staging area that lives during each request.
*   _**Token**_
    *    The token class generates access tokens and hashes passwords.
*   _**Session**_
    *    The session class provides a convenience wrapper for the PHP user session, as well as methods to create or destroy sessions.
*   _**Database**_
    *    The database class serves as a wrapper for the PDO database api.
*   _**Clean**_
    *    The clean class sanitizes user input

---

## Foundation Interfaces ##
*   _**ITableRowGateway**_
    *    Provides methods to read and write from a database
*   _**IDataMap**_
    *    Provides a means to map internal data types to database query results

---

## IMGDUEL Objects ##
*   _**User**_
    *    The imgduel user object, including user name, email, and authentication tokens
*   _**Vote**_
    *    The imgduel vote reference class
*   _**Image**_
    *    The imgduel image reference, including file path and hash token
*   _**Duel**_
    *    The duel class, which contains references to two image objects and a user.  ALso contains a reference token.
