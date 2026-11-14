# AdminStyleKonkat

AdminStyleKonkat is an optional styling module for ProcessWire's Konkat admin
theme, which is part of AdminThemeUikit. It provides a replacement stylesheet
for the admin interface, including forms, buttons, menus, notices and page lists.

It keeps Konkat's familiar appearance and settings while bringing its styling
together into one compiled stylesheet. It does not replace AdminThemeUikit,
change your content or add a new admin interface.

## Why Use It?

The original theme loads several stylesheets, with later rules overriding
earlier ones. AdminStyleKonkat combines the required base styles and Konkat
styles, reducing duplicated rules and the need for overlapping overrides.

For users, the aim is more consistent styling across admin controls, with
continued support for Konkat's light and dark modes and colour settings.
For theme authors, the Less files group related styles by component, making
them easier to find, adjust and maintain.

It replaces three theme stylesheets with one. Other necessary stylesheets,
such as those supplied by input modules, still load normally.

## Requirements

- AdminThemeUikit
- PHP 7.4 or newer

The development environment uses ProcessWire 3.0.273 dev and UIkit 3.25.19.
Compatibility with older ProcessWire versions has not been established.
Node.js and Less are needed only if you want to rebuild the CSS, not to install
or use the module.

## Installation

1. Copy the AdminStyleKonkat folder into site/modules/.
2. In the ProcessWire admin, open Modules and refresh the module list.
3. Find **Admin Style Konkat** and install it.
4. In AdminThemeUikit's settings, select the default Konkat style if it is not already selected.
5. Reload an admin page to see the replacement styling.

The compiled CSS is included, so there is nothing to build during installation.
The module does not modify your saved theme settings.
Other admin themes and AdminThemeUikit styles are left unchanged.

If your site already uses custom hooks to replace the admin stylesheets, remove
or disable those hooks before using this module. They can prevent the module
from finding the original stylesheet or cause both versions to load.

## Using the Theme

Continue using AdminThemeUikit's existing settings to choose your main colour,
light or dark mode, and options such as checkbox switches. There is no separate
settings screen for AdminStyleKonkat and no palette editor is included.

Your existing custom CSS settings remain available. The module leaves the
theme's admin-custom.css file, fonts, JavaScript, optional theme styles and
module styles in place.

To return to the original styling, uninstall Admin Style Konkat and reload the
admin page. Your theme preferences and content are not changed.

## What It Changes

When AdminThemeUikit renders an admin page using the default Konkat style,
AdminStyleKonkat:

- Replaces Konkat's admin.css link with its own stylesheet in the same position.
- Removes the separate UIkit base stylesheet pw.min.css and AdminTheme.css,
because their required styles are included in the replacement.

If its selected CSS file is missing or empty, or the original Konkat stylesheet
link cannot be found, it leaves the page's stylesheet links unchanged.

## Editing and Building

You only need this section if you want to change the theme's Less sources.
All component files are in less/. Files beginning with uk- contain UIkit
component styling; files beginning with pw- contain ProcessWire styling.
The entry file is less/build.less, and shared configuration is in
less/_config.less.

The build uses the UIkit sources supplied with your ProcessWire installation
at wire/modules/AdminTheme/AdminThemeUikit/uikit/. There is no separate UIkit
copy to maintain in this module. Updating ProcessWire may update those sources,
so check the result when rebuilding after an upgrade.

Install Node.js, then install the compiler versions used for development:

```sh
npm install --global less@4.9.1 less-plugin-clean-css@1.6.0
```

On Windows, run build.bat from the module folder. It generates admin.css and
admin.min.css at the module root.

On other platforms, run these commands from the module folder:

```sh
lessc --rewrite-urls=all less/build.less admin.css
lessc --rewrite-urls=all --clean-css less/build.less admin.min.css
```

## Limitations

This module has not yet been fully tested with on all browsers or all third-party
modules. Check the admin pages and editing workflows used by your site before
deploying it.

Some colour combinations, particularly on coloured sections and in dark-mode
alerts, still need contrast improvements. Choosing a custom colour does not
guarantee that every control will meet accessibility contrast requirements.
