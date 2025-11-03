<?php
class PaymentSystem {
    private $usersFile = "users.json";
    private $transactionsFile = "transactions.json";
    private $refundsFile = "refunds.json";

    private $users = [];
    private $transactions = [];
    private $refunds = [];

    private $fees = [
        "Credit Card" => 0.02,
        "PayPal" => 0.03,
        "Cryptocurrency" => 0.01
    ];

    public function __construct() {
        $this->users = $this->loadFile($this->usersFile);
        $this->transactions = $this->loadFile($this->transactionsFile);
        $this->refunds = $this->loadFile($this->refundsFile);
    }

    private function loadFile($file) {
        return file_exists($file) ? json_decode(file_get_contents($file), true) ?? [] : [];
    }

    private function saveFile($file, $data) {
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function saveAll() {
        $this->saveFile($this->usersFile, $this->users);
        $this->saveFile($this->transactionsFile, $this->transactions);
        $this->saveFile($this->refundsFile, $this->refunds);
    }

    public function getUsers() {
        return $this->users;
    }

    public function makePayment($user, $amount, $method) {
        if (!isset($this->users[$user])) {
            $this->users[$user] = ["balance" => 200000];
        }

        // Checking if there is any fraudulent
        if ($amount > 100000) {
            return ["status" => "error", "message" => "Transaction flagged as fraudulent (amount exceeds R100,000)"];
        }

        $fee = $amount * $this->fees[$method];
        $total = $amount + $fee;

        if ($this->users[$user]["balance"] < $total) {
            return ["status" => "error", "message" => "Insufficient balance"];
        }

        $this->users[$user]["balance"] -= $total;

        $txn = [
            "id" => "TXN" . str_pad(count($this->transactions) + 1, 4, "0", STR_PAD_LEFT),
            "user" => $user,
            "method" => $method,
            "amount" => $amount,
            "fee" => $fee,
            "total" => $total,
            "status" => "Success",
            "timestamp" => date("Y-m-d H:i:s")
        ];

        $this->transactions[] = $txn;
        $this->saveAll();

        return ["status" => "success", "message" => "Payment processed successfully", "txn" => $txn];
    }

    public function requestRefund($txnId, $reason) {
        foreach ($this->transactions as $txn) {
            if ($txn["id"] === $txnId) {
                $this->refunds[] = [
                    "refund_id" => "RFD" . str_pad(count($this->refunds) + 1, 4, "0", STR_PAD_LEFT),
                    "txn_id" => $txnId,
                    "user" => $txn["user"],
                    "amount" => $txn["total"],
                    "reason" => $reason,
                    "status" => "Pending",
                    "timestamp" => date("Y-m-d H:i:s")
                ];
                $this->saveAll();
                return ["status" => "success", "message" => "Refund request submitted"];
            }
        }
        return ["status" => "error", "message" => "Transaction not found"];
    }

    public function approveRefund($refundId) {
        foreach ($this->refunds as &$r) {
            if ($r["refund_id"] === $refundId && $r["status"] === "Pending") {
                $r["status"] = "Approved";
                $user = $r["user"];
                $amount = $r["amount"];
                $this->users[$user]["balance"] += $amount;
                $this->saveAll();
                return ["status" => "success", "message" => "Refund approved and amount credited"];
            }
        }
        return ["status" => "error", "message" => "Refund not found or already processed"];
    }

    public function getTransactions($user = null) {
        if ($user) {
            return array_reverse(array_filter($this->transactions, fn($t) => $t["user"] === $user));
        }
        return array_reverse($this->transactions);
    }

    public function getRefunds() {
        return array_reverse($this->refunds);
    }
}
?>
