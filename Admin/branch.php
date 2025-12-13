<?php
session_start();
// Include i18n bootstrap
require_once __DIR__ . '/../bootstrap/i18n.php';
$companyName = "FOJ Express";
$current_lang = get_current_lang();
$is_rtl = is_rtl();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>" dir="<?php echo $is_rtl ? 'rtl' : 'ltr'; ?>">

<?php include 'pages/head.php'; ?>
<?php include 'admin.php'; ?>
<?php if (isEmployee()) { header("Location: index.php"); exit(); } ?>

<body>
    <div id="app">
        <?php include 'pages/sidebar.php'; ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3><?php __e('admin_branches_title'); ?></h3>
                <p class="text-muted"><?php __e('admin_manage_branches'); ?></p>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="branch-header">
                                    <button class="btn-add-branch" data-bs-toggle="modal" data-bs-target="#BranchModal">
                                        <i class="bi bi-plus-circle"></i> <?php __e('admin_add_new_branch'); ?>
                                    </button>
                                </div>

                                <div class="search-container">
                                    <div class="search-wrapper">
                                        <i class="bi bi-search search-icon"></i>
                                        <input type="text" 
                                               id="branchSearch" 
                                               class="search-input" 
                                               placeholder="<?php __e('admin_search_branches'); ?>">
                                        <button type="button" class="search-clear" id="clearBranchSearch" style="display: none;">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                    <div class="search-results-info" id="branchSearchResultsInfo" style="display: none;">
                                        <span id="branchResultsCount">0</span> <?php __e('admin_branches_found'); ?>
                                    </div>
                                </div>

                                <div class="branches-list">
                                    <?php
                                    $getall = getAllBranch();
                                    $has_branches = false;

                                    while ($row = mysqli_fetch_assoc($getall)) {
                                        $has_branches = true;
                                        $branch_id = $row['branch_id'];
                                        ?>
                                        <div class="branch-item"
                                             data-branch-name="<?php echo strtolower(htmlspecialchars($row['branch_name'])); ?>">
                                            <div class="branch-item-header">
                                            </div>

                                            <div class="branch-item-body">
                                                       <div class="branch-field">
                                                           <label><?php __e('admin_branch_name'); ?></label>
                                                           <input type="text" 
                                                                  id="branch_name_<?php echo $branch_id; ?>"
                                                                  class="form-control editable-input" 
                                                                  value="<?php echo htmlspecialchars($row['branch_name']); ?>" 
                                                                  onchange="updateData(this, '<?php echo $branch_id; ?>', 'branch_name', 'branch', 'branch_id')">
                                                       </div>
                                            </div>

                                            <div class="branch-item-footer">
                                                       <button type="button"
                                                               onclick="deleteData(<?php echo $branch_id; ?>, 'branch', 'branch_id')"
                                                               class="btn-delete" 
                                                               title="<?php __e('admin_delete_branch'); ?>">
                                                           <i class="bi bi-trash"></i> <?php __e('admin_delete_branch'); ?>
                                                       </button>
                                            </div>
                                        </div>
                                        <?php
                                    }

                                           if (!$has_branches) {
                                               echo '<div class="empty-state">
                                                   <i class="bi bi-building" style="font-size: 48px; color: #6c757d; margin-bottom: 16px;"></i>
                                                   <h4>' . __t('admin_no_branches') . '</h4>
                                                   <p>' . __t('admin_no_branches_desc') . '</p>
                                               </div>';
                                           }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <?php include 'pages/footer.php'; ?>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="BranchModal" tabindex="-1" aria-labelledby="BranchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                       <div class="modal-header">
                           <h5 class="modal-title" id="BranchModalLabel"><?php __e('admin_add_new_branch'); ?></h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php __e('close'); ?>"></button>
                       </div>
                       <form action="" method="post" id="branchForm" data-parsley-validate="" enctype="multipart/form-data">
                           <div class="modal-body">
                               <div class="form-field">
                                   <label for="branch_name" class="form-label"><?php __e('admin_branch_name'); ?></label>
                                   <input type="text" 
                                          class="form-control" 
                                          name="branch_name" 
                                          id="branch_name"
                                          placeholder="<?php __e('admin_enter_branch_name'); ?>" 
                                          required>
                               </div>
                           </div>
                           <div class="modal-footer">
                               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php __e('cancel'); ?></button>
                               <button type="button" onclick="addBranch(this.form)" name="submit" class="btn btn-primary">
                                   <i class="bi bi-check-circle"></i> <?php __e('admin_save_branch'); ?>
                               </button>
                           </div>
                       </form>
            </div>
        </div>
    </div>

    <style>
        .page-heading p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
        }

        .branch-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }

        .btn-add-branch {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .btn-add-branch:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-icon {
            position: absolute;
            left: 16px;
            color: #6c757d;
            font-size: 18px;
            z-index: 1;
        }

        .search-input {
            width: 100%;
            padding: 12px 45px 12px 45px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-clear {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .search-clear:hover {
            background: #e9ecef;
            color: #495057;
        }

        .search-results-info {
            margin-top: 12px;
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
        }

        .branch-item.hidden {
            display: none;
        }

        .branches-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
            max-height: calc(100vh - 300px);
            overflow-y: auto;
            padding-right: 8px;
        }

        .branches-list::-webkit-scrollbar {
            width: 8px;
        }

        .branches-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .branches-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .branches-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .branch-item {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .branch-item:hover {
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
            border-color: #d0d0d0;
            transform: translateY(-1px);
        }

        .branch-item-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .branch-id-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .branch-item-body {
            padding: 12px 14px;
            flex: 1;
        }

        .branch-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .branch-field label {
            font-size: 10px;
            font-weight: 600;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .editable-input {
            width: 100%;
            padding: 8px 10px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .editable-input:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .editable-input:hover {
            border-color: #ced4da;
            background: #ffffff;
        }

        .branch-item-footer {
            padding: 10px 14px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 1200px) {
            .branches-list {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .branches-list {
                grid-template-columns: 1fr;
                max-height: none;
            }
        }

        .btn-delete {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-delete:hover {
            background: #dc2626;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state h4 {
            margin: 16px 0 8px;
            color: #495057;
            font-size: 18px;
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
            border-radius: 12px 12px 0 0;
            padding: 20px 24px;
        }

        .modal-title {
            color: white;
            font-weight: 600;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 24px;
        }

        .form-field {
            margin-bottom: 20px;
        }

        .form-field:last-child {
            margin-bottom: 0;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 16px 24px;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
    </style>

    <?php if ($is_rtl): ?>
    <link rel="stylesheet" href="../css/rtl.css">
    <?php endif; ?>

    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const searchInput = $('#branchSearch');
            const clearButton = $('#clearBranchSearch');
            const resultsInfo = $('#branchSearchResultsInfo');
            const resultsCount = $('#branchResultsCount');
            const branchItems = $('.branch-item');

            searchInput.on('input', function() {
                if ($(this).val().length > 0) {
                    clearButton.show();
                } else {
                    clearButton.hide();
                    resultsInfo.hide();
                }
            });

            clearButton.on('click', function() {
                searchInput.val('');
                $(this).hide();
                resultsInfo.hide();
                filterBranches('');
            });

            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase().trim();
                filterBranches(searchTerm);
            });

            function filterBranches(searchTerm) {
                let visibleCount = 0;
                if (searchTerm === '') {
                    branchItems.removeClass('hidden');
                    resultsInfo.hide();
                    return;
                }
                branchItems.each(function() {
                    const $item = $(this);
                    const branchName = $item.data('branch-name') || '';
                    if (branchName.includes(searchTerm)) {
                        $item.removeClass('hidden');
                        visibleCount++;
                    } else {
                        $item.addClass('hidden');
                    }
                });
                resultsCount.text(visibleCount);
                resultsInfo.show();
            }
        });
    </script>
</body>

</html>