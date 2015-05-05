// Linkable table rows
// Source: http://stackoverflow.com/a/12089140
$('tr').on("click", function() {
    if($(this).data('href') !== undefined){
        document.location = $(this).data('href');
    }
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

/**
 * Clone first table row, clear all input values before appending to table body
 * @param selector Table Selector
 */
function addTableRow(selector) {
  var row = $(selector+' tbody tr').first().clone();
  row.find('input[name!="questions[\'module\'][]"]').val('');
  $(selector).find('tbody').append(row);
}

/**
 * Delete the table row containing the selector if it is not the last one
 * @param selector Upward traverse from here to table row for deletion
 */
function removeTableRow(selector) {
  $(selector).closest('tr:not(:only-child)').remove();
}

$('.nav-tabs-sticky').stickyTabs({ backToTop: true });

$('.module').has('.answers').css('display', 'block');
