This file serves as a brief overview to help you get set up, importing the data and running the api locally or on your web host.

Basic requirements:

Apache (with ability to use .htaccess files)
PHP > 5.0
MySql > 4.1

Important files:

/library/settings.ini contains all of the database connection information you will need to connect to your database, and you should fill it in as appropriate

Checkout the git repo to the root of your webserver, so that you will have a translink/ folder at the root. This file is within that folder.

Structure:

Shared folders/files

Data: Contains all the data files required for import, the metro files in the metro/ folder and the nir files in the nirailways/ folder.
classes: Contains helper files to parse data
library: database settings and connections

Metro/Nir specific are split into appropriate folders, which have an identical structure within them of:

api: api handlers for the appropriate data types
import: contains read_cif.php which will import the appropriate data for you. Before you import, create a blank db with the appropriate schema as detailed below.

DB schema:

Metro: metro/import/metro_schema.sql

.htaccess:

When uploading to your server, make sure that the file metro/api/.htaccess has been included.

Then, you should be able to go to www.webhost.com/translink/metro/api/<methods and junk> to access the metro api, and nirailways for the nir api

To import the data, just go to /translink/metro/import/read_cif.php and magic will ensue.