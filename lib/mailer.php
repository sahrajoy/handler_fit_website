<?php
// E-Mail-Versand. Der Transport wird in config.php gewählt (log, smtp oder mail).

use PHPMailer\PHPMailer\PHPMailer;

function send_mail(string $to, string $subject, string $body, ?string $replyTo = null, ?string $replyName = null): bool
{
    $transport = config('mail.transport');

    if (is_demo()) {
        db()->prepare('INSERT INTO demo_mails (recipient, subject, body, created_at) VALUES (?, ?, ?, ?)')
            ->execute([$to, $subject, $body, date('Y-m-d H:i:s')]);
        return true;
    }

    if ($transport === 'log') {
        $entry = str_repeat('=', 70) . "\n"
            . 'Datum:    ' . date('Y-m-d H:i:s') . "\n"
            . "An:       $to\n"
            . ($replyTo ? "Reply-To: $replyTo\n" : '')
            . "Betreff:  $subject\n\n$body\n\n";
        return file_put_contents(dirname(config('db_path')) . '/mail.log', $entry, FILE_APPEND) !== false;
    }

    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        if ($transport === 'smtp') {
            $mail->isSMTP();
            $mail->Host = config('mail.smtp_host');
            $mail->Port = (int)config('mail.smtp_port');
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.smtp_user');
            $mail->Password = config('mail.smtp_pass');
            $mail->SMTPSecure = config('mail.smtp_secure') === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->isMail();
        }
        $mail->setFrom(config('mail.from'), config('mail.from_name'));
        $mail->addAddress($to);
        if ($replyTo) {
            $mail->addReplyTo($replyTo, $replyName ?? '');
        }
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->send();
        return true;
    } catch (Throwable $ex) {
        error_log('Mailversand fehlgeschlagen: ' . $ex->getMessage());
        return false;
    }
}
