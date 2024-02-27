<div class="form-group {{ $errors->has($field['name']) ? ' has-error' : '' }}">
    <label for="{{ $field['name'] }}">{{ $field['label'] }}</label>
    <img id="preview-{{ $field['name'] }}"
         src="{{ asset('/uploads/noimage.jpg') }}"
         height="200px" width="200px"/>
    <input type="file"
           name="{{ $field['name'] }}"
           value="{{ old($field['name'], \setting($field['name'])) }}"
           class="form-control {{ array_get( $field, 'class') }}"
           id="{{ $field['name'] }}"
           placeholder="{{ $field['label'] }}"
           style="display:none">
    <a href="javascript:{{ $field['name'] }}();">Upload</a> |
    <a style="color: red" href="javascript:remove_{{ $field['name'] }}()">Remove</a>
    <input type="hidden" style="display: none" value="0" name="removes-{{ $field['name'] }}" id="removes-{{ $field['name'] }}">

    @if ($errors->has($field['name'])) <small class="help-block">{{ $errors->first($field['name']) }}</small> @endif
</div>

<script>
        
    function company_header_logo() {
        $('#{!! $field['name'] !!}').click();
    }
    $('#{!! $field['name'] !!}').change(function () {
        var imgPath = $(this)[0].value;
        var ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg")
            readURLHeaderLogo(this);
        else
            alert("Please select image file (jpg, jpeg, png).")
    });
    function readURLHeaderLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            reader.onload = function (e) {
                $('#preview-{{ $field['name'] }}').attr('src', e.target.result);
                $('#removes-{{ $field['name'] }}').val(0);
            }
        }
    }
    function remove_company_header_logo() {
        $('#preview-{{ $field['name'] }}').attr('src', '{{url('uploads/noimage.jpg')}}');
        $('#removes-{{ $field['name'] }}').val(1);
    }

    function company_footer_logo() {
        $('#company_footer_logo').click();
    }
    $('#company_footer_logo').change(function () {
        var imgPath = $(this)[0].value;
        var ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg")
            readURLHeaderLogo(this);
        else
            alert("Please select image file (jpg, jpeg, png).")
    });
    function readURLFooterLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.readAsDataURL(input.files[0]);
            reader.onload = function (e) {
                $('#preview-company_footer_logo').attr('src', e.target.result);
                $('#removes-company_footer_logo').val(0);
            }
        }
    }
    function remove_company_footer_logo() {
        $('#preview-company_footer_logo').attr('src', '{{url('uploads/noimage.jpg')}}');
        $('#removes-company_footer_logo').val(1);
    }

</script>