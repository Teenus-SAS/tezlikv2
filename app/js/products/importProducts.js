$(document).ready(function() {
    $('.cardImportProducts').hide();

    $('#btnNewImportProducts').click(function(e) {
        e.preventDefault();
        $('.cardCreateProduct').hide(800);
        $('.cardImportProducts').toggle(800);
    });

    let selectedFile;
    //console.log(window.XLSX);

    $('#fileProducts').change(function(e) {
        e.preventDefault();
        selectedFile = e.target.files[0];
    });

    /* let data = [{
        "name": "jayanth",
        "data": "scd",
        "abc": "sdef"
    }]
 */

    $('#btnImportProducts').click(function(e) {
        e.preventDefault();
        debugger
        file = $('#fileProducts').val();

        if (!file) {
            toastr.error('Seleccione un archivo');
            return false
        }

        //XLSX.utils.json_to_sheet(data, 'out.xlsx');
        if (selectedFile) {
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(selectedFile);
            fileReader.onload = (event) => {
                let data = event.target.result;
                let workbook = XLSX.read(data, { type: "binary" });
                workbook.SheetNames.forEach(sheet => {
                    let rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                    console.log(rowObject);
                });
            }
        }
    });

});