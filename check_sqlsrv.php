<?php
if (function_exists('sqlsrv_connect')) {
    echo "✅ sqlsrv extension is loaded.";
} else {
    echo "❌ sqlsrv extension NOT loaded.";
}
?>