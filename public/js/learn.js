function submit(id_spreadsheet, url) {
    document.getElementById("submit").disabled = true;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("result-detail").innerHTML = this.responseText;
            var x = document.getElementById("result");
            if (x.style.display === "none") {
                x.style.display = "block";
            }
        }
    };
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    xmlhttp.send("id_spreadsheet=" + id_spreadsheet);
}

function setForm(number) {
    if (number > 0) {
        document.getElementById("cell-answers").innerHTML = '';
        var str = "";
        for (i=0; i<number; i++) {
            str = str.concat('<div class="col-3" style="margin-bottom:1rem;"> <input type="text" name="cells[]" class="form-control"></div>');
        }
        document.getElementById("cell-answers").innerHTML = str;
    }
}