RollerworksPasswordStrengthBundle
=================================

This bundle provides a validator for ensuring strong passwords in Symfony2 applications.

Passwords are validated using strength-levels (weak, medium, strong etc).

If you want to use a more detailed configuration (pattern requirement), you can use the PasswordStrengthBundle
provided by [John Bafford](https://github.com/jbafford/PasswordStrengthBundle).

    You can use this bundle and the one provided by John Bafford side by side without any conflict.

    Its however not recommended to use both the pattern-requirement and strength-level constraint
    at the same property/method, as both provide similar functionality.

## (WIP)
    Checking the password against a weak/forbidden password database is planned.

## Installation

### Step 1: Using Composer (recommended)

To install RollerworksPasswordStrengthBundle with Composer just add the following to your
`composer.json` file:

```js
// composer.json
{
    // ...
    require: {
        // ...
        "rollerworks/password-strength-bundle": "master-dev"
    }
}
```

**NOTE**: Please replace `master-dev` in the snippet above with the latest stable
branch, for example ``1.0.*``.

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

```bash
$ php composer.phar update rollerworks/password-strength-bundle
```

Now, Composer will automatically download all required files, and install them
for you. All that is left to do is to update your ``AppKernel.php`` file, and
register the new bundle:

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle(),
    // ...
);
```

### Step 1 (alternative): Using ``deps`` file (Symfony 2.0.x)

First, checkout a copy of the code. Just add the following to the ``deps``
file of your Symfony Standard Distribution:

```ini
[RollerworksPasswordStrengthBundle]
    git=https://github.com/rollerworks/PasswordStrengthBundle.git
    target=/bundles/Rollerworks/Bundle/PasswordStrengthBundle
```

**NOTE**: You can add `version` tag in the snippet above with the latest stable
branch, for example ``version=origin/1.0``.

Then register the bundle with your kernel:

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle(),
    // ...
);
```

Make sure that you also register the namespace with the autoloader:

```php
<?php

// app/autoload.php
$loader->registerNamespaces(array(
    // ...
    'Rollerworks'              => __DIR__.'/../vendor/bundles',
    // ...
));
```

Now use the ``vendors`` script to clone the newly added repositories
into your project:

```bash
$ php bin/vendors install
```

### Step 1 (alternative): Using submodules (Symfony 2.0.x)

If you're managing your vendor libraries with submodules, first create the
`vendor/bundles/Rollerworks/Bundle` directory:

``` bash
$ mkdir -pv vendor/bundles/Rollerworks/Bundle
```

Next, add the necessary submodule:

``` bash
$ git submodule add git://github.com/rollerworks/PasswordStrengthBundle.git vendor/bundles/Rollerworks/Bundle/PasswordStrengthBundle
```

### Step2: Configure the autoloader

Add the following entry to your autoloader:

``` php
<?php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Rollerworks'              => __DIR__.'/../vendor/bundles',
    // ...
));
```

### Step3: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle(),
    // ...
);
```

Congratulations! You're ready!

## Basic Usage

### Strength validation

You can use the ```Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints\PasswordStrength```
constraint with the following options.

* message: The validation message (default: password_to_weak)
* minLength: Minimum length of the password, should be at least 6 (or 8 for better security)
* minStrength: Minimum required strength of the password.

The strength is computed from various measures including
length and usage of (special) characters.

    Note: A strength is measured by the presence of a character and total length.
    One can have a 'medium' password consisting of only a-z and A-Z, but with a length higher then 12 characters.

    If the password consists of only numbers or a-z/A-Z the final strength is decreased.

The strengths are marked up as follow.

*  1: Very Weak (any character)
*  2: Weak (at least one lower and capital)
*  3: Medium (at least one lower and capital and number)
*  4: Strong (at least one lower and capital and number) (recommended for most usage)
*  5: Very Strong (recommended for admin or finance related service)

If you are using annotations for validation, include the constraints namespace:

```php
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;
```

and then add the PasswordStrength validator to the relevant field:

```php

/**
 * @RollerworksPassword\PasswordStrength(minLength=7, minStrength=3)
 */
protected $password;
```