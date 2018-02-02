# Datatables Plugin

The **Datatables** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It provides a shortcode to embed the awesome [DataTables](https://datatables.net) jQuery plugin.

## Installation

Installing the Datatables plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install datatables

This will install the Datatables plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/datatables`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `datatables`. You can find these files on [GitHub](https://github.com/finanalyst/grav-plugin-datatables) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/datatables

> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error),
[Problems](https://github.com/getgrav/grav-plugin-problems) and [ShortcodeCore](https://github.com/getgrav/grav-plugin-shortcode-core)
to operate.

## Configuration

The aim is to keep configuration to a minimum. So the only configuration is to turn the plugin off and on.

Before configuring this plugin, you should copy the `user/plugins/datatables/datatables.yaml` to `user/config/plugins/datatables.yaml` and only edit that copy.

Here is the default configuration:

```yaml
enabled: true
```

## Usage

All that is needed is for the body content to contain `[datatables]<!--- A table in md format[/datatables]`.

It is also possible to have an inner shortcode that generates an HTML table, such at the `[sql-table]` provided by the `sqlite` grav plugin.

### Options
The options all relate to the DataTable plugin which are [exhaustively documented here](https://datatables.net/reference/option/).

For example:
```md
[datatables paging=false ordering=false info=false]
Table code
[/datatables]
```
will generate (something like) the following json object, which in turn is provided to the DataTable() function.
```json
{
        "paging":   false,
        "ordering": false,
        "info":     false
    }
```
## Credits

All the credit is due to the people at `https://datatables.net`.

## To Do

- [ ] Future plans, if any
