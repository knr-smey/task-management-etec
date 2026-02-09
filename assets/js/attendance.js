(function () {
    const form = document.getElementById('attendanceForm');

    if (!form) return;

    const apiUrl = form.getAttribute('data-api-url') || '';

    const rows = Array.from(form.querySelectorAll('[data-member-row]'));

    function updateReasonVisibility(row) {
        const selected = row.querySelector('input[type="radio"]:checked');
        const reasonInput = row.querySelector('.permission-reason');
        if (!reasonInput) return;

        if (selected && selected.value === 'permission') {
            reasonInput.classList.remove('hidden');
        } else {
            reasonInput.classList.add('hidden');
            reasonInput.value = '';
        }
    }

    rows.forEach((row) => {
        row.addEventListener('change', (event) => {
            if (event.target && event.target.type === 'radio') {
                updateReasonVisibility(row);
            }
        });
        updateReasonVisibility(row);
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const csrf = form.querySelector('input[name="csrf"]')?.value || '';
        const teamId = form.querySelector('input[name="team_id"]')?.value || '';
        const attendanceDate = form.querySelector('input[name="attendance_date"]')?.value || '';

        const records = [];
        const missing = [];
        const missingReasons = [];

        rows.forEach((row) => {
            const userId = row.getAttribute('data-user-id');
            const selected = row.querySelector('input[type="radio"]:checked');
            const reasonInput = row.querySelector('.permission-reason');
            const reason = reasonInput ? reasonInput.value.trim() : '';

            if (!selected) {
                missing.push(userId);
                return;
            }

            if (selected.value === 'permission' && reason === '') {
                missingReasons.push(userId);
                return;
            }

            records.push({
                user_id: Number(userId),
                status: selected.value,
                reason: reason
            });
        });

        if (missing.length > 0) {
            Swal.fire('Missing selection', 'Please choose a status for all members.', 'warning');
            return;
        }

        if (missingReasons.length > 0) {
            Swal.fire('Reason required', 'Please add a reason for permission.', 'warning');
            return;
        }

        if (!apiUrl) {
            Swal.fire('Error', 'API endpoint missing.', 'error');
            return;
        }

        const body = new FormData();
        body.append('csrf', csrf);
        body.append('team_id', teamId);
        body.append('attendance_date', attendanceDate);
        body.append('records', JSON.stringify(records));

        try {
            const res = await fetch(apiUrl, {
                method: 'POST',
                body: body,
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await res.json();
            if (data.status) {
                Swal.fire('Saved', 'Attendance records saved.', 'success');
            } else {
                Swal.fire('Error', data.message || 'Save failed.', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Server error.', 'error');
        }
    });
})();
