<?php
header('Content-Type: application/json');
echo process();

/**
 * Processing POST data
 * JSON response
 * @return string
 */
function process() : string
{
    if(empty($_POST)){
        return json_encode([
            'success' => false,
            'message' => 'No data passed!'
        ]);
    }
    $output = [];

    switch ($_POST['action']) {
        case "add":
            $output = add(
                [
                    'phone' => $_POST['phone'],
                    'email' => $_POST['email']
                ]
            );
            break;
        case "retrieve":
            $output = retrieve($_POST['email']);
            break;
    }

    return json_encode($output);
}

/**
 * Adding record to DB
 * @param array $data
 * @return array
 */
function add(Array $data) : array
{
    $output = [];

    $pdo = pdo();
    $statement = $pdo->prepare("INSERT INTO users (phone,email) VALUES (:phone,:email)");
    $result = $statement->execute($data);

    if (!$result) {
        $output['success'] = false;
        $output['message'] = "There was an error while recording data!";
    } else{
        $output['success'] = true;
        $output['message'] = "Data recorded successfully!";
        $output['data'] = $result;
    }

    return $output;
}

/**
 * Retrieving record from e-mail
 * And sending phone to user
 * @param String $email
 * @return array
 */
function retrieve(String $email) : array
{
    $output = [];

    $pdo = pdo();
    $statement = $pdo->prepare("SELECT phone,email FROM users WHERE email=:email");
    $statement->execute(['email' => $email]);
    $user =  $statement->fetch();

    if (empty($user)) {
        $output['success'] =  false;
        $output['message'] =  "There was an error while retrieving data.";
    } else {
        sendEmail($user['email'], "Your phone: {$user['phone']}");
        $output['success'] = true;
        $output['message'] = "Your phone has been emailed to you!";
    }

    return $output;
}

/**
 * Sending an e-mail
 * @param String $to
 * @param String $body
 * @return bool
 */
function sendEmail(String $to, String $body) : bool
{

    $subject = "Your phone";
    $headers  = "Content-type: text/html; charset=utf-8 \r\n";
    $headers .= "From: Sender <user@test.com>\r\n";
    $headers .= "Reply-To: reply-user@test.com\r\n";

    return mail($to, $subject, $body, $headers);
}

/**
 * Returning PDO connection
 * @return PDO
 */
function pdo() : PDO
{
    $db = [
        'host' => 'localhost',
        'db' => 'auslogics',
        'user' => 'homestead',
        'password' =>  'secret'
    ];

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO(
        "mysql:dbname={$db['db']};host={$db['host']}",
        $db['user'],
        $db['password'],
        $options
    );

}