<?php
// subscriptions.php
// Assuming $subscriptions is already fetched in admindashboard.php

// Prepare data for charts (e.g., number of subscriptions per month)
$subscriptionsPerMonth = [];
foreach ($subscriptions as $subscription) {
    $month = date('M Y', strtotime($subscription['subscription_date']));
    if (!isset($subscriptionsPerMonth[$month])) {
        $subscriptionsPerMonth[$month] = 0;
    }
    $subscriptionsPerMonth[$month]++;
}

// Sort the months chronologically
uksort($subscriptionsPerMonth, function($a, $b) {
    return strtotime("01 $a") - strtotime("01 $b");
});
?>
<section id="subscriptions" class="content-section">
    <h2>Subscriptions</h2>
    <div class="card">
        <canvas id="subscriptionsChart"></canvas>
    </div>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Subscription Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscriptions as $subscription): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($subscription['id']); ?></td>
                        <td><?php echo htmlspecialchars($subscription['email']); ?></td>
                        <td><?php echo htmlspecialchars($subscription['subscription_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script>
        // Subscriptions Chart
        const ctxSubscriptions = document.getElementById('subscriptionsChart').getContext('2d');
        const subscriptionsChart = new Chart(ctxSubscriptions, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_keys($subscriptionsPerMonth)); ?>,
                datasets: [{
                    label: 'New Subscriptions',
                    data: <?php echo json_encode(array_values($subscriptionsPerMonth)); ?>,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, precision:0 }
                }
            }
        });
    </script>
</section>
