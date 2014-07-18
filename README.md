LavaCharts [![Build Status](https://travis-ci.org/kevinkhill/LavaCharts.png?branch=master)](https://travis-ci.org/kevinkhill/LavaCharts)  

[![Latest Stable Version](https://poser.pugx.org/khill/lavacharts/v/stable.svg)](https://packagist.org/packages/khill/lavacharts) [![Total Downloads](https://poser.pugx.org/khill/lavacharts/downloads.svg)](https://packagist.org/packages/khill/lavacharts) [![License](https://poser.pugx.org/khill/lavacharts/license.svg)](https://packagist.org/packages/khill/lavacharts)

==========

LavaCharts is a package for Composer that wraps the Google Chart API for PHP5.3+

Aimed at Laravel 4, but will work with any PHP project.


#Installing
In your project's main ```composer.json``` file, add this line to the requirements:

  ```
  "khill/lavacharts" : "1.0.x"
  ```

Run Composer to install LavaCharts:

  ```
  composer update
  ```

Next, register LavaCharts in your app by adding this line to the providers array in ```app/config/app.php```:

  ```
  'providers' => array(
     ...

     "Khill\Lavacharts\LavachartsServiceProvider"
  );
  ```

*Don't worry about the Alias, it is set up for you :)*

If you want to view the demos, publish the assets with:

  ```
  php artisan asset:publish khill/lavacharts
  ```


##Like My Work?
Feel like buying me a coffee? [Any amount donated to is greatly apprecieated :)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FLP6MYY3PYSFQ)

- - -

##[MIT License](http://opensource.org/licenses/MIT)
```
Copyright (c) 2013, Kevin Hill of KHill Designs

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.
```
