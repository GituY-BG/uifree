$(document).ready(function () {
    // Profile selection handler
    $('#profile-select').change(function () {
        let profile = $(this).val() || '';
        console.log('Profile selected:', profile);
        fetchSessions({ profile: profile, page: 1 });
    });

    // Search form handler
    $('#search-user-form').submit(function (e) {
        e.preventDefault();
        let username = $('#search-username').val().trim();
        let profile = $('#profile-select').val();
        if (!username) {
            alert('Username tidak boleh kosong');
            return;
        }
        fetchSessions({ profile: profile, username: username, page: 1 });
    });

    // Pagination click handler
    $(document).on('click', '.ajax-page', function (e) {
        e.preventDefault();
        let page = $(this).text();
        if ($(this).hasClass('next')) {
            page = parseInt($('#pagination-container .active .page-link').text()) + 1;
        } else if ($(this).hasClass('prev')) {
            page = parseInt($('#pagination-container .active .page-link').text()) - 1;
        }
        let profile = $('#profile-select').val();
        let username = $('#search-username').val().trim();
        fetchSessions({ profile: profile, username: username, page: page });
    });

    // Function to fetch sessions and update UI
    function fetchSessions(params) {
        $.ajax({
            url: ajaxGetSessionsUrl,
            type: 'POST',
            data: params,
            dataType: 'json',
            success: function (response) {
                // Update stats
                $('#stats-container').html(`
    < div class="col-md-4" >
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pengguna Aktif</h5>
                <p class="card-text">${response.active_users} Pengguna</p>
            </div>
        </div>
                    </ >
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Upload</h5>
                                <p class="card-text">${response.total_upload} GB</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Download</h5>
                                <p class="card-text">${response.total_download} GB</p>
                            </div>
                        </div>
                    </div>
`);

                // Update user status
                if (response.user_status) {
                    $('#user-status').html(`< p > Status: ${response.user_status.status}</ > `);
                } else {
                    $('#user-status').html('');
                }

                // Update sessions table
                let tbody = '';
                response.sessions.forEach(function (session) {
                    tbody += `
    < tr >
                            <td>${session.username}</td>
                            <td>${session.framedipaddress}</td>
                            <td>${session.acctstarttime}</td>
                            <td>${(session.acctinputoctets / (1024 * 1024)).toFixed(2)}</td>
                            <td>${(session.acctoutputoctets / (1024 * 1024)).toFixed(2)}</td>
                        </ >
    `;
                });
                $('#sessions-tbody').html(tbody);

                // Update pagination
                $('#pagination-container').html(response.pagination);

                // Update chart
                if (response.chart_labels && response.chart_data) {
                    window.updateChart(response.chart_labels, response.chart_data);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', error);
                alert('Gagal memuat data. Silakan coba lagi.');
            }
        });
    }
});
