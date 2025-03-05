<?php
$secret = "your-github-webhook-secret"; // Set this in GitHub Webhook settings

$signature = $_SERVER["HTTP_X_HUB_SIGNATURE_256"] ?? "";
$payload = file_get_contents("php://input");

if ($signature !== "sha256=" . hash_hmac("sha256", $payload, $secret)) {
    http_response_code(403);
    exit("Invalid signature");
}

exec("sh /var/www/scbt/deploy.sh > /var/www/scbt/deploy.log 2>&1 &");
http_response_code(200);
echo "Deployment started!";
