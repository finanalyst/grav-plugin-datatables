# Datatables Plugin

The **Datatables** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It provides two shortcodes to embed the awesome [DataTables](https://datatables.net) jQuery plugin (v1.10.16).

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

### [datatables] Shortcode
All that is needed is for the body content to contain
```md
[datatables]
<!--- A table in md format -->
[/datatables]
```
It is also possible to have an inner shortcode that generates an HTML table, such at the `[sql-table]` provided by the `sqlite` grav plugin.

>Note In the event that the content between `[datatables] ... [/datatables]` does not have a valid `<table> ... </table>` HTML container, an error message is returned with the offending content.

#### Table id
jQuery plugins require a selector, and the `DataTables` plugin typically uses the table id.

By the time `[datatables]` is processed, the content should be an HTML Table.

There are three ways the `id` can be assigned:  
1. The HTML Table already is of the form `<table  id="SomeID">`
2. The `grav-id` option to `[datatables]` is assigned (see below).
3. `[datatables]` assigns a random string.

>Note: if the id provided by alteratives (1) or (2) are illegal, then a random string will be assigned as the id.

#### Options to [datatables]
All but one option (`grav-id`) relate to the DataTable plugin which are [exhaustively documented here](https://datatables.net/reference/option/).

For example:
```md
[datatables paging=false ordering=false info=false]
|Field 1|Field2|
|---|---|
|Data|1234|
[/datatables]
```
will generate (something like) the following
```HTML
<table id="qwerty">
  <thead><tr><th>Field 1</th><th>Field2</th></tr></thead>
  <tbody><tr><td>Data</td><td>1234</td></tr></tbody>
</table>
<script>
$(document).ready( function () {
    $('#qwerty').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false
    });
  });
</script>
```

#### `grav-id`
In order to allow the developer to provide a specific `id`, say to link with other code, it can be added as an option to `[datatables]`, eg.
```md
[datatables grav-id=SomeId]
```
The Id must be valid HTML, which for HTML 5 is a letter and any number of non-space characters.

### [dt-script] Shortcode
In order to access the full `DataTables` jQuery plugin, extra code needs to be added to the function.
In addition, it is necessary to pass on the unique `id` of the table to the code.

This can all be done using the `[dt-script]` inner shortcode.
So long as the shortcode is inside the `[datatables]` code, it will be added to the initialisation function.

The `id` of the `<table>` is provided as the JS variable `selector`. This variable can then be used as in the examples given in the DataTables documentation.

For example:
```md
[datatables paging=false ordering=false info=false]
|Field 1|Field2|
|---|---|
|Data|1234|
  [dt-script]
    var table = $(selector).DataTable();
    $(selector + ' tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
  [/dt-script]
[/datatables]
```
This gets rendered as:
```HTML
<table id="qwerty">
  <thead><tr><th>Field 1</th><th>Field2</th></tr></thead>
  <tbody><tr><td>Data</td><td>1234</td></tr></tbody>
</table>
<script>
$(document).ready( function () {
    $('#qwerty').DataTable({
        "paging":   false,
        "ordering": false,
        "info":     false
    });
    var table = $('#qwerty').DataTable();
    $('#qwerty' + ' tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
  });
</script>
```
## Limitations
This version of the shortcode does not allow for DataTable plugins. This should be fairly easy to add, perhaps by including plugin configuration codes for each plugin required.  

However, it would be interesting to see whether there is any need to add plugins.

## Credits

All the credit is due to the people at `https://datatables.net`.

The version of DataTables is given in the heading.

## To Do

- [ ] Handle new versions of DataTables automatically.
- [ ] Add DataTable plugin support.
