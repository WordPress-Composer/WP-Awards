<?php
namespace Voting\Helper;

use PHPMailer;
use Config\Config;
use Voting\Exception\EmailException;

/**
 * @author VL
 *
 * Provides a simple interface for interacting with PHPMailer package
 */

trait Email
{
    /**
     * Wrapper method for PHPMailer package
     * @param  [type] $message Message to be emailed
     * @param  [type] $email   Email address to send the email to
     * @param  [type] $subject Email subject
     */
    public function sendMail($message, $email, $subject)
    {
        if(strtolower(getenv("SMTP_AUTH")) === 'true') {
            $mail = $this->getConfiguredSMTPAuthMail();
        } else {
            $mail = $this->getConfiguredSMTPMail();
        }
        $mail->setFrom(getenv("FROM_EMAIL"), getenv("FROM_NAME"));
        $mail->addAddress($email);
        $mail->isHTML(false);

        $mail->Subject = $subject;
        $mail->Body    = $message;

        if (!$mail->send()) {
            error_log('Message could not be sent.');
            error_log('Mailer Error: ' . $mail->ErrorInfo);
            throw new EmailException('Cannot send email');
        }
    }

    /**
     * Configure SMTP settings via env
     * @return [type] [description]
     */
    private function getConfiguredSMTPMail() {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Timeout = 20;
        $mail->Host = getenv("SMTP_HOST");
        $mail->Port = getenv("SMTP_PORT");
        return $mail;
    }

    /**
     * Configure SMTP settings via env with Auth
     * @return [type] [description]
     */
    private function getConfiguredSMTPAuthMail() {
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Timeout = 20;
        $mail->Username = getenv("SMTP_USERNAME");
        $mail->Password = getenv("SMTP_PASSWORD");
        $mail->SMTPSecure = 'tls';
        $mail->Host = getenv("SMTP_HOST");
        $mail->Port = getenv("SMTP_PORT");
        return $mail;
    }

    /**
     * Sends an email to the voter to confirm the nomination
     * @param  int    $categoryId       category id
     * @param  string $userName         Name of the user
     * @param  string $userEmail        Email of the user
     * @param  string $nomineeName      Nominee name
     * @param  string $nominationReason Nomination reason
     */
    private function sendVoterEmails($categoryId, $userName, $userEmail, $nomineeName, $nominationReason)
    {
        $config = Config::getInstance();
        $categories = $config['isa-config']['categories'];

        $voterMessage = '';
        $voterMessage .=  "Hello " . $userName . ",\r\n\r\n";
        $voterMessage .=  "Thanks for your nomination. This email confirms that we have received your vote for the following candidate:\r\n\r\n";
        $voterMessage .=  "Category: " . $categories[$categoryId]['name'] . "\r\n";
        $voterMessage .=  "Nominee: " . $nomineeName . "\r\n\r\n";
        $voterMessage .=  "Awards Vote\r\n\r\n";
        $voterMessage .=  "============================\r\n\r\n";
        $voterMessage .=  "This is an automated messager";

        $this->sendMail($voterMessage, $userEmail, "Thanks for your nomination");
    }

    /**
     * Sends an email to  admin to confirm the nomination
     * @param  [type] $categoryId       category id
     * @param  [type] $userName         Name of the user
     * @param  [type] $userEmail        Email of the user
     * @param  [type] $nomineeName      Nominee name
     * @param  [type] $nominationReason Nomination reason
     */
    private function sendISAEmails($categoryId, $userName, $userEmail, $nomineeName, $nominationReason, $sendEmail)
    {
        $config = Config::getInstance();
        $categories = $config['isa-config']['categories'];

        $isaMessage = '';
        $isaMessage .=  "Hello,\r\n\r\n";
        $isaMessage .=  "You have received a new nominee. The details are as follows:\r\n\r\n";
        $isaMessage .=  "Category: " . $categories[$categoryId]['name'] . "\r\n";
        $isaMessage .=  "Nominee: " . $nomineeName . "\r\n";
        $isaMessage .=  "Voter Name: " . $userName . "\r\n";
        $isaMessage .=  "Voter Email: " . $userEmail . "\r\n";
        $isaMessage .=  "Reason: " . $nominationReason . "\r\n\r\n";
        $isaMessage .=  "============================\r\n\r\n";
        $isaMessage .=  "This is an automated messager";

        $this->sendMail($isaMessage, $sendEmail, "New nomination received");
    }
}
