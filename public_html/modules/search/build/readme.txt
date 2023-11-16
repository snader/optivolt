Templates
Current version: v1.1.0
Created by: Remco Borst

=========================

Adds search to the website, includes front-end views which are customisable by class name.
Install file will install everything for you, after installing any of the following modules you must run the installer again:
- catalog
- photoalbum
- news
- page

Pagemanager is required because you search on a page.

The search module groups search results from multiple modules together and adds a score which is definable by you per column.
You are completely free in how you create the search queries. See the standard controller for examples.

Before the search is executed it searches for existing searchword redirects, this is useful for example: "Auto kopen" is the searchword, you want to redirect this to the product "Car 1"
Search results are in this case not needed, these are definable by the user, but will be auto filled by peoples searchwords.

=========================