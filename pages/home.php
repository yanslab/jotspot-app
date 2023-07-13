<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card rounded-0 mb-3">
            <div class="card-header rounded-0">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    <div class="card-title col-auto flex-shrink-1 flex-grow-1"><strong>List of Notes</strong></div>
                    <div class="col-auto">
                        <button class="btn btn-primary rounded-0 btn-sm bg-gradient" id="add_new_note" type="button"><i class="fa-solid fa-plus"></i> Add New</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <?php 
            $notes = $master->list_notes();
            foreach($notes as $note):
            ?>
            <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-3 px-2">
                <div class="card h-100 shadow note-item rounded-0">
                    <div class="card-header rounded-0">
                        <div class="card-title text-truncate"><?= $note['title'] ?></div>
                    </div>
                    <div class="card-body rounded-0">
                        <div class="lh-1 fw-lighter text-muted fst-italic truncate-3"><?= $note['description'] ?></div>
                        
                    </div>
                    <div class="card-footer rounded-0">
                        <div class="d-flex w-100 align-items-center">
                            <div class="col-auto">
                                <a href="javascrip:void(0)" data-id="<?= $note['id'] ?>" data-is-pinned="<?= $note['pinned'] ?>" class="px-2 py-1 pin-note" title="<?= $note['pinned'] == 0? 'Pin to top' : 'Unpin' ?>">
                                    <?php if($note['pinned'] == 0): ?>
                                        <i class="fa-solid fa-thumbtack text-secondary opacity-75"></i>
                                    <?php else: ?>
                                        <i class="fa-solid fa-thumbtack text-primary opacity-100"></i>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="col-auto">
                                <a  href="javascrip:void(0)" data-id="<?= $note['id'] ?>" class="px-2 py-1 edit-note  opacity-75" title="Edit Note">
                                        <i class="fa-solid fa-file-pen"></i>
                                </a>
                            </div>
                            <div class="col-auto">
                                <a  href="javascrip:void(0)" data-id="<?= $note['id'] ?>" class="px-2 py-1 delete-note opacity-75 text-danger" title="Delete Note">
                                        <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                            <div class="col-auto">
                                <a  href="javascrip:void(0)" data-id="<?= $note['id'] ?>" class="px-2 py-1 view-note opacity-75 text-dark" title="View Note">
                                        <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>