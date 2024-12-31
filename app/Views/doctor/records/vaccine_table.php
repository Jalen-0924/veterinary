<table class="table table-bordered table-hover" id="vaccineHistoryTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Weight</th>
            <th>Vaccine</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($vaccine_records)) : ?>
            <?php foreach ($vaccine_records as $vaccine) : ?>
                <tr data-id="<?= $vaccine['id'] ?? '' ?>">
                    <td data-date="<?= $vaccine['vaccine_date'] ?>"><?= date('M d, Y', strtotime($vaccine['vaccine_date'])) ?></td>
                    <td><?= htmlspecialchars($vaccine['weight']) ?></td>
                    <td><?= htmlspecialchars($vaccine['vaccine']) ?></td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm editBtn">
                            <i class="align-middle" data-feather="edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm deleteBtn">
                            <i class="align-middle" data-feather="trash"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4" class="text-center">No records found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
Last edited 1 minute ago