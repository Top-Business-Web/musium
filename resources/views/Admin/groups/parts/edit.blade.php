<div class="modal-header">
    <h5 class="modal-title" id="example-Modal3">Edit Group</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('group.update',$group->id)}}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-control-label">Group Title</label>
            <input type="text" value="{{$group->title}}" class="form-control" name="title" id="title">
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="addButton">Create</button>
        </div>

    </form>
</div>

<script>
    $('.dropify').dropify()
</script>
