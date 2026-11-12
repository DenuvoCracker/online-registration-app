<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$errors = [];
$submitted = false;
$data = [
    'full_name' => '',
    'email' => '',
    'phone' => '',
    'dob' => '',
    'gender' => '',
    'course' => '',
    'address' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($data as $key => $value) {
        $data[$key] = trim($_POST[$key] ?? '');
    }

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        $errors[] = 'Your session expired. Please refresh the page and try again.';
    }

    if ($data['full_name'] === '' || mb_strlen($data['full_name']) < 2) {
        $errors[] = 'Please enter your full name (at least 2 characters).';
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (!preg_match('/^[0-9+\-\s()]{10,15}$/', $data['phone'])) {
        $errors[] = 'Please enter a valid phone number.';
    }
    if ($data['dob'] === '') {
        $errors[] = 'Please select your date of birth.';
    } else {
        $dob = DateTime::createFromFormat('Y-m-d', $data['dob']);
        if (!$dob || $dob->format('Y-m-d') !== $data['dob'] || $dob > new DateTime('today')) {
            $errors[] = 'Please enter a valid date of birth.';
        }
    }
    if (!in_array($data['gender'], ['Female', 'Male', 'Other', 'Prefer not to say'], true)) {
        $errors[] = 'Please select a gender option.';
    }
    if (!in_array($data['course'], ['Computer Science', 'Information Technology', 'Electronics', 'Mechanical', 'Civil', 'Business Administration'], true)) {
        $errors[] = 'Please select a course.';
    }
    if ($data['address'] === '' || mb_strlen($data['address']) < 8) {
        $errors[] = 'Please enter your complete address.';
    }
    if (empty($_POST['declaration'])) {
        $errors[] = 'Please accept the declaration before submitting.';
    }

    if (!$errors) {
        $submitted = true;
        // Rotate the token after a successful submission.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Online student registration application form">
    <title><?= $submitted ? 'Application Submitted' : 'Online Registration' ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/script.js" defer></script>
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php"><span class="brand-mark">R</span> Register<span class="brand-light">Now</span></a>
    <span class="topbar-note">ONLINE APPLICATION PORTAL</span>
</header>

<main class="page-shell">
<?php if ($submitted): ?>
    <section class="success-card">
        <div class="success-icon">✓</div>
        <p class="eyebrow">APPLICATION RECEIVED</p>
        <h1>Thank you, <?= e($data['full_name']) ?>!</h1>
        <p class="success-copy">Your registration form has been submitted successfully. Here is a summary of the information you provided.</p>
        <div class="reference">APPLICATION STATUS <strong>Submitted successfully</strong></div>
        <h2 class="summary-heading">Application details</h2>
        <dl class="details-grid">
            <div><dt>Full name</dt><dd><?= e($data['full_name']) ?></dd></div>
            <div><dt>Email address</dt><dd><?= e($data['email']) ?></dd></div>
            <div><dt>Phone number</dt><dd><?= e($data['phone']) ?></dd></div>
            <div><dt>Date of birth</dt><dd><?= e(date('d M Y', strtotime($data['dob']))) ?></dd></div>
            <div><dt>Gender</dt><dd><?= e($data['gender']) ?></dd></div>
            <div><dt>Course applied for</dt><dd><?= e($data['course']) ?></dd></div>
            <div class="full-width"><dt>Address</dt><dd><?= nl2br(e($data['address'])) ?></dd></div>
        </dl>
        <div class="success-actions">
            <button class="button button-primary" onclick="window.print()">Print application</button>
            <a class="button button-secondary" href="index.php">Submit another response</a>
        </div>
    </section>
<?php else: ?>
    <section class="intro">
        <p class="eyebrow">WELCOME TO REGISTRATION</p>
        <h1>Start your application.</h1>
        <p>Complete the form below with your details. Fields marked with <span class="required">*</span> are required.</p>
    </section>

    <?php if ($errors): ?>
        <div class="error-box" role="alert">
            <strong>Please correct the following:</strong>
            <ul><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <section class="form-card">
        <div class="form-heading">
            <div><p class="eyebrow">REGISTRATION FORM</p><h2>Personal information</h2></div>
            <span class="step-label">STEP 1 OF 1</span>
        </div>
        <form id="registrationForm" method="POST" action="index.php" novalidate>
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <div class="form-grid">
                <div class="field">
                    <label for="full_name">Full name <span class="required">*</span></label>
                    <input id="full_name" name="full_name" type="text" placeholder="Enter your full name" value="<?= e($data['full_name']) ?>" required minlength="2">
                    <small class="field-error"></small>
                </div>
                <div class="field">
                    <label for="email">Email address <span class="required">*</span></label>
                    <input id="email" name="email" type="email" placeholder="you@example.com" value="<?= e($data['email']) ?>" required>
                    <small class="field-error"></small>
                </div>
                <div class="field">
                    <label for="phone">Phone number <span class="required">*</span></label>
                    <input id="phone" name="phone" type="tel" placeholder="+91 98765 43210" value="<?= e($data['phone']) ?>" required>
                    <small class="field-error"></small>
                </div>
                <div class="field">
                    <label for="dob">Date of birth <span class="required">*</span></label>
                    <input id="dob" name="dob" type="date" max="<?= date('Y-m-d') ?>" value="<?= e($data['dob']) ?>" required>
                    <small class="field-error"></small>
                </div>
                <div class="field">
                    <label for="gender">Gender <span class="required">*</span></label>
                    <select id="gender" name="gender" required>
                        <option value="">Select an option</option>
                        <?php foreach (['Female', 'Male', 'Other', 'Prefer not to say'] as $option): ?>
                            <option value="<?= e($option) ?>" <?= $data['gender'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="field-error"></small>
                </div>
                <div class="field">
                    <label for="course">Course applied for <span class="required">*</span></label>
                    <select id="course" name="course" required>
                        <option value="">Choose a course</option>
                        <?php foreach (['Computer Science', 'Information Technology', 'Electronics', 'Mechanical', 'Civil', 'Business Administration'] as $option): ?>
                            <option value="<?= e($option) ?>" <?= $data['course'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="field-error"></small>
                </div>
                <div class="field full-width">
                    <label for="address">Residential address <span class="required">*</span></label>
                    <textarea id="address" name="address" rows="3" placeholder="House number, street, city, state and PIN code" required><?= e($data['address']) ?></textarea>
                    <small class="field-error"></small>
                </div>
            </div>
            <label class="declaration">
                <input type="checkbox" name="declaration" id="declaration" value="yes" required>
                <span>I confirm that the information provided above is accurate to the best of my knowledge. <span class="required">*</span></span>
            </label>
            <small class="field-error declaration-error"></small>
            <div class="form-footer">
                <span class="secure-note">Your information is used for this demonstration only.</span>
                <button type="submit" class="button button-primary" id="submitButton">Submit application <span aria-hidden="true">→</span></button>
            </div>
        </form>
    </section>
    <p class="footer-note">© <?= date('Y') ?> RegisterNow · Online Application Portal</p>
<?php endif; ?>
</main>
</body>
</html>
