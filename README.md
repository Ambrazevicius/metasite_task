Description
========================

This task is based on Symfony3 framework, together with bootstrap, ReactJS and GULP toolkit.

Users are able to register to newsletter subscription.
- name
- email
- categories they want to get news about

Admins are able to add subscribers and edit their information.
Also posibility to delete subscribers and sort by email, time and name.

All data is saving in *.json file

Setup
========================

1. Clone this repository
2. run command "composer install"
3. Visit website url


Information
========================

Website url is: http://localhost/metasite_task/web/

if URL is changing, please follow steps bellow:

open file "web/js/list.js"
And replace url of "list_url" and "delete_url" BUT don't replace string after web/...

admin:

url: http://localhost/metasite_task/web/listing
user: admin<br/>
pass: metasite.


Need more categrories?
You will find function called getSelection, which is in Subscribers model.(Entity)

Enjoy!


Deividas Ambrazevicius
info@supportofda.com