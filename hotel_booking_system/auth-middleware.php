<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ensures the user is authenticated.
 * Returns the user data array if logged in, or terminates with Unauthorized.
 */
function authenticate_user() {
    // Check if user data exists in session (or adjust if using token-based auth)
    if (!isset($_SESSION["user_id"])) {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "Unauthorized access. Please log in first."
        ]);
        exit();
    }

    return [
        "user_id" => $_SESSION["user_id"],
        "username" => $_SESSION["username"] ?? "",
        "role" => $_SESSION["role"] ?? "guest"
    ];
}

/**
 * Ensures the authenticated user has one of the allowed roles.
 * Terminate with 403 Forbidden if they don't have permission.
 */
function authorize_roles($allowed_roles = []) {
    $user = authenticate_user();

    //is role found in $allowed_roles?
    if (!in_array($user["role"], $allowed_roles)) {
        http_response_code(403);//i know you but you're not allowed
        echo json_encode([
            "status" => "error",
            "message" => "Forbidden: You do not have permission to access this resource."
        ]);
        exit();
    }
    //we get to this point only if the user is logged in and their role was on the list
    return $user;
}
?>