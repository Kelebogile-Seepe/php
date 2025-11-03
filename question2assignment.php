<?php
class PaymentSystem {
    private $users = [];
    private $transactions = [];
    private $fees = [
        "Credit Card" => 0.02,
        "PayPal" => 0.03,
        "Crypto" => 0.01
    ];

    // Add user
    public function addUser($name, $balance) {
        $this->users[$name] = [
            "balance" => $balance
        ];
    }

    // Make a payment
    public function makePayment($user, $amount, $method) {
        if (!isset($this->users[$user])) {
            return "❌ Error: User not found.\n";
        }

        if (!array_key_exists($method, $this->fees)) {
            return "❌ Error: Invalid payment method.\n";
        }

        if ($amount > 100000) {
            return "🚨 Payment rejected due to fraud detection.\n";
        }

        $fee = $amount * $this->fees[$method];
        $total = $amount + $fee;

        if ($this->users[$user]["balance"] < $total) {
            return "❌ Error: Insufficient balance.\n";
        }

        // Deduct total
        $this->users[$user]["balance"] -= $total;

        // Record transaction
        $transaction = [
            "user" => $user,
            "amount" => $amount,
            "method" => $method,
            "fee" => $fee,
            "status" => "successful",
            "timestamp" => date("Y-m-d H:i:s")
        ];

        $this->transactions[] = $transaction;

        return "✅ Payment successful using $method. Fee: R" . number_format($fee, 2) .
               ". Updated balance: R" . number_format($this->users[$user]["balance"], 2) . "\n";
    }

    // Refund a payment
    public function requestRefund($user, $amount) {
        foreach (array_reverse($this->transactions) as $txn) {
            if ($txn["user"] === $user && $txn["amount"] == $amount && $txn["status"] === "successful") {
                $this->users[$user]["balance"] += $amount;

                $refund = [
                    "user" => $user,
                    "amount" => $amount,
                    "method" => $txn["method"],
                    "status" => "refunded",
                    "timestamp" => date("Y-m-d H:i:s")
                ];

                $this->transactions[] = $refund;

                return "💰 Refund issued for R$amount to $user.\n";
            }
        }
        return "❌ Error: No matching transaction found for refund.\n";
    }

    // Show all transactions
    public function showHistory() {
        echo "📜 Transaction History:\n";
        foreach ($this->transactions as $txn) {
            echo "- {$txn['timestamp']} | {$txn['user']} | R{$txn['amount']} | {$txn['method']} | {$txn['status']}\n";
        }
    }
}

// -------------------------
// TEST CASES
// -------------------------
$system = new PaymentSystem();

// Add users
$system->addUser("Alice", 50000);
$system->addUser("Bob", 150000);
$system->addUser("Charlie", 10000);

// Test cases (from your table)
echo $system->makePayment("Alice", 20000, "Credit Card");   // ✅ success
echo $system->makePayment("Bob", 120000, "PayPal");         // 🚨 fraud rejection
echo $system->makePayment("Charlie", 5000, "Crypto");       // ✅ success
echo $system->requestRefund("Alice", 20000);                // 💰 refund
echo $system->makePayment("Alice", 1000, "Bitcoin");        // ❌ unsupported method

// Display history
$system->showHistory();
?>
