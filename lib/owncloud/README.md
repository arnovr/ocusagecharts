ocUsageCharts owncloud package
==============================
This package exists for the pure purpose to abstract owncloud dependencies from the library code
When owncloud decides to upgrade stuff, and they will, the adjustment only needs to be adjusted within this package.
This way the business logic does not need to be changed.