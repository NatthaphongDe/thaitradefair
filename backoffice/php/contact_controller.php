<?php

// include("../Mailjet/Client.php");
// include("../Mailjet/Config.php");
// include("../Mailjet/Request.php");
// include("../Mailjet/Resources.php");
// include("../Mailjet/Response.php");

class ContactController
{
    public function contact_send_mail($request) {
        print_r($request);
        // $detail = [
        //     'name' => $request["reply_name"],
        //     'email' => $request["reply_email"],
        //     'telephone' => $request["reply_tel"],
        //     'subject' => $request["reply_subject"],
        //     'mesage' => $request["reply_mesage"],
        //     'mail' => $request["reply_mails"],
        // ];

        // $mail_test = "thanet.w@ibusiness.co.th";
        // $message = "
        // <div style=\"background: #439F47;padding: 5px 10px 40px 10px;border-radius: 10px;\">
        //     <div class=\"wrapper\" style=\"background: #fff;padding: 20px;\"><br>
        //       <div style=\"line-height: 41px;border-radius: 5px;color: #2b9a59;text-align: left;\">Send To " . $detail['name'] . "</div>
        //       <div style=\"text-align:left;color:#000;\">Mail" . $detail['email'] . "</div><br>
        //       <div style=\"text-align:left;\"><h3><span style=\"color: #2b9a59;\">" . $detail['subject'] . "</h3></span></div><br>
        //       <div style=\"text-align:left;\">" . $detail['mesage'] . "</div><br><br>
        //       <div style=\"border:2px dashed #ccc;padding: 10px;\">
        //       Best Regards,<br>". $detail['mesage'] ."
        //       </div>
        //     </div>
        //   </center>
        // </div>";

        $body = [
            'Messages' => [
                [
                'From' => [
                    'Email' => "admin@thaitradefair.com",
                    'Name' => "IT Dept"
                ],
                'To' => [
                    [
                        'Email' => "thanet.w@ibusiness.co.th",
                        'Name' => "Recipiant1"
                    ],
                ],

                'Subject' => "Your Daily Data is Here",
                'HTMLPart' => "<center><h2>Your Daily Work Totals are Below:</h2></center><br />  " . " <h2>Entry Date/Time:</h2> " . date("F j, Y, g:i a")  . " <h2>Order:</h2> " . $_POST['Order_Quantity'] . " <h2>Product:</h2> " . $_POST['Product_Quantity'] . "<h2>Employee Count:</h2> " . $_POST['Crew_Count'] . " <h2>Daily Total:</h2> " . $sum = array_sum($_SESSION) . " <center><h2>Link to Report:</h2></center> <h3>google.com</h3> " 
                ]
            ]
        ];

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json')
        );

            
        curl_setopt($ch, CURLOPT_USERPWD, "13c1c2d532c2a4e8092e196f986ae8bc");

        $server_output = curl_exec($ch);

        curl_close ($ch);
        sleep(10);

        $response = json_decode($server_output);
        if ($response->Messages[0]->Status == 'success' )  {
            echo "Email sent successfully.";
        }   


        echo $response;
    }

//Fetch Results
} 
