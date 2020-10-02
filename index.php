<html>
    <head><title>hey</title>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    </head>
    <body>
    <form method="post" id="s" enctype="multipart/form-data">
        <h3>file uploader</h3>
        <input type="file" id="file_to_upload" name="file_to_upload" /><br>
        <label> enable password <input type="checkbox" onclick="show_pass_input()" id="x" /></label>
        <input type="text" id="password" name="password" placeholder="password" style="visibility: hidden" /><br>
        <input onclick="upload_file(new FormData($('#s')[0]), document.getElementById('percent'), document.getElementById('out_link'), document.getElementById('password').value)" type="button" id="submit" value="submit" /><br><br>
        progress : <progress id="percent" value="0">0</progress>
    </form>output link : <a id="out_link"></a>
    <script>
        function show_pass_input(){
            let pi = document.getElementById('password');

            if(document.getElementById('x').checked)
                pi.style.visibility = 'visible';
            else
                pi.style.visibility = 'hidden';
        }

        function upload_file(form_data, progress_bar, out_link, pass_value){
            let ctx_url = (pass_value.length === 0)
                ? 'api/upload/' : 'api/upload/?password=' + pass_value;

            $.ajax({
                type: 'POST',
                url: ctx_url,
                data: form_data,
                contentType: false,
                processData: false,
                cache: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();

                    xhr.upload.onprogress = function(per){
                        if(per.lengthComputable) {
                            progress_bar.max = per.total;
                            progress_bar.value = per.loaded;
                        }
                    };

                    return xhr;
                }
            }).done(function(data){
                var json_data = JSON.parse(data);

                if(json_data.success) {
                    var file_data = JSON.parse(data).file_data;

                    out_link.textContent = file_data.name + '.' + file_data.extension;
                    out_link.href = file_data.link;
                }
                else{
                    alert(json_data.message);
                }
            });
        }
    </script>
    </body>
</html>
