
<div class="modal fade" id="addSpeFiles" tabindex="-1" role="dialog" aria-labelledby="cancelModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload SPE File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_spe_file" enctype="multipart/form-data" action="{{ url('uploadSpeFiles') }}">
                    @csrf
                    <div class="spe_file" id="spe_file">
                        <div class="row">
                            {{-- <div class="col-lg-12 mb-3">
                                <!-- <label for="name">Name</label> -->
                                <input type="hidden" name="name[]" class="form-control" id="name" placeholder="Enter Name">
                            </div> --}}
                            <!-- @if(authCheckIfItsRnd(auth()->user()->department_id))
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is Confidential:</label>
                                        <input type="hidden" name="is_confidential[0]" value="0">
                                        <input type="checkbox" name="is_confidential[0]" value="1">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Is For Review:</label>
                                        <input type="hidden" name="is_for_review[0]" value="0">
                                        <input type="checkbox" name="is_for_review[0]" value="1">
                                    </div>
                                </div>
                            @endif -->
                            <div class="col-lg-12 mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name[]" class="form-control fileNames" id="fileNames" placeholder="">
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <div class="group-confidential">
                                        <label>Is Confidential :</label>
                                        <input type="checkbox" name="is_confidential[]" value="1" onclick="toggleConfidential()">
                                        <input type="hidden" name="is_confidential[]" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="form-group">
                                    <div class="group-review">
                                        <label>Is For Review :</label>
                                        <input type="checkbox" name="is_for_review[]" value="1" onclick="toggleReview()">
                                        <input type="hidden" name="is_for_review[]" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label for="spe_file">Browse Files</label>
                                <input type="file" class="form-control" id="spe_file" name="spe_file[]" onchange="uploadFiles()">
                            </div>
                            <div class="col-lg-12">
                                <input type="hidden" class="form-control" name="spe_id" value="{{ $data->id }}">
                            </div>
                        </div>
                    </div> 
                    <div id="speButton">
                        <button type="button" class="btn btn-sm btn-primary" onclick="addSpeFile()"><i class="ti-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteSpeFile()"><i class="ti-trash"></i></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" value="Receive" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function addSpeFile()
    {
        const newRow = document.createElement("div")
        newRow.classList.add('row')
        newRow.innerHTML = `
            <div class="col-lg-12 mb-3">
                <label for="name">Name</label>
                <input type="text" name="name[]" class="form-control fileNames" id="fileNames" placeholder="">
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <div class="group-confidential">
                        <label>Is Confidential :</label>
                        <input type="checkbox" name="is_confidential[]" value="1" onclick="toggleConfidential()">
                        <input type="hidden" name="is_confidential[]" value="0">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <div class="group-review">
                        <label>Is For Review :</label>
                        <input type="checkbox" name="is_for_review[]" value="1" onclick="toggleReview()">
                        <input type="hidden" name="is_for_review[]" value="0">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <label for="spe_file">Browse Files</label>
                <input type="file" class="form-control" id="spe_file" name="spe_file[]" onchange="uploadFiles()">
            </div>
            <div class="col-lg-12">
                <input type="hidden" class="form-control" name="spe_id" value="{{ $data->id }}">
            </div>
        `

        document.getElementById('spe_file').append(newRow)
    }

    function deleteSpeFile()
    {
        const lastElement = document.getElementById('spe_file')
        const lastChildElement = lastElement.lastChild

        if (lastElement.children.length > 1)
        {
            lastElement.removeChild(lastChildElement)
        }
    }

    function uploadFiles()
    {
        const name = event.target.files[0].name
        const fileNames = event.target.closest('.row').querySelector('.fileNames');
        fileNames.value = name
    }

    function toggleConfidential()
    {
        var closestRow = event.target.closest('.row')
        var confidentialInput = closestRow.querySelector('input[type="checkbox"][name="is_confidential[]"]')
        
        if (confidentialInput.checked)
        {
            var hiddenInput = closestRow.querySelector('input[type="hidden"][name="is_confidential[]"]').remove()
        }
        else
        {
            var hiddenInput = document.createElement('input')
            hiddenInput.type = 'hidden'
            hiddenInput.name = 'is_confidential[]'
            hiddenInput.value = 0

            closestRow.querySelector('.group-confidential').appendChild(hiddenInput)
        }
    }

    function toggleReview()
    {
        var closestRow = event.target.closest('.row')
        var confidentialInput = closestRow.querySelector('input[type="checkbox"][name="is_for_review[]"]')
        
        if (confidentialInput.checked)
        {
            var hiddenInput = closestRow.querySelector('input[type="hidden"][name="is_for_review[]"]').remove()
        }
        else
        {
            var hiddenInput = document.createElement('input')
            hiddenInput.type = 'hidden'
            hiddenInput.name = 'is_for_review[]'
            hiddenInput.value = 0

            closestRow.querySelector('.group-review').appendChild(hiddenInput)
        }
    }

</script>
{{-- <script>
    $(document).ready(function() {
        function addSseForm() {
            const rowIndex = $('.spe_file').length; // Get the current number of rows to set a unique index

            const newProductForm = `
                <div class="spe_file">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <input type="hidden" name="name[]" class="form-control" placeholder="Enter Name">
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="spe_file">Browse Files</label>
                            <input type="file" class="form-control" name="spe_file[${rowIndex}]">
                        </div>
                        <div class="col-lg-12">
                            <input type="hidden" class="form-control" name="spe_id" value="{{ $data->id }}">
                        </div>
                        <div class="col-lg-12">
                            <button type="button" class="btn btn-sm btn-primary addSpeFile"><i class="ti-plus"></i></button>
                            <button type="button" class="btn btn-sm btn-danger deleteRowBtn" hidden><i class="ti-trash"></i></button>
                        </div>
                    </div>
                </div>`;

            $('.spe_file').last().find('.addSpeFile').hide();
            $('.spe_file').last().find('.deleteRowBtn').show();
            $('.spe_file').last().after(newProductForm);
        }
    
        $(document).on('click', '.addSpeFile', function() {
            addSseForm();
        });
    
        $(document).on('click', '.deleteRowBtn', function() {
            var currentRow = $(this).closest('.spe_file');
            
            if ($('.spe_file').length > 1) {
                if ($('.spe_file').last().is(currentRow)) {
                    currentRow.prev().find('.addSpeFile').show();
                    currentRow.prev().find('.deleteRowBtn').show();
                }
                currentRow.remove();
            }
            
            if ($('.spe_file').length === 1) {
                $('.spe_file').find('.addSpeFile').show();
                $('.spe_file').find('.deleteRowBtn').hide();
            }
        });
    
        $(document).on('change', 'input[type="file"]', function() {
            var filename = $(this).val().split('\\').pop();
            $(this).closest('.spe_file').find('input[name="name[]"]').val(filename);
        });
    
        if ($('.spe_file').length === 1) {
            $('.spe_file').find('.deleteRowBtn').hide();
        }
    });
</script> --}}

<!-- @if(authCheckIfItsRnd(auth()->user()->department_id))
    <div class="col-lg-12">
        <div class="form-group">
            <label>Is Confidential:</label>
            <input type="hidden" name="is_confidential[${rowIndex}]" value="0">
            <input type="checkbox" name="is_confidential[${rowIndex}]" value="1">
        </div>
    </div>
    <div class="col-lg-12">
        <div class="form-group">
            <label>Is For Review:</label>
            <input type="hidden" name="is_for_review[${rowIndex}]" value="0">
            <input type="checkbox" name="is_for_review[${rowIndex}]" value="1">
        </div>
    </div>
@endif -->