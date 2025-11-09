<div class="card">
    <div class="card-header">
        <h3>Recent Visitors</h3>
        <a href="admin_visitor_logs.php" class="btn btn-secondary">View All</a>
    </div>
    <div class="card-body">
        <?php if (empty($recentVisitors)): ?>
            <div class="empty-state">
                <i class="fas fa-user-secret"></i>
                <p>No visitor data recorded yet.</p>
            </div>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Location</th>
                        <th>Page Visited</th>
                        <th>Referrer</th>
                        <th>Visit Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentVisitors as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                            <td><?php echo htmlspecialchars(get_country_from_ip($log['ip_address'])); ?></td>
                            <td><?php echo htmlspecialchars($log['page_visited']); ?></td>
                            <td>
                                <?php if ($log['referrer']): ?>
                                    <a href="<?php echo htmlspecialchars($log['referrer']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo htmlspecialchars(parse_url($log['referrer'], PHP_URL_HOST)); ?>
                                    </a>
                                <?php else: ?>
                                    Direct Visit
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y H:i:s', strtotime($log['visit_time'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>