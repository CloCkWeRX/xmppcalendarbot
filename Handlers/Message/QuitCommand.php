<?php
class QuitCommand implements MessageCommand {
    public function exec($conn, $args, $data) {
        @list(, $way) = $args;

        if ($way == "now") {
            $conn->message($data['from'], "Nap time! Zzzzz");
        } else {
            $lines = array();
            $lines[] = "Just what do you think you're doing, Dave?";
            $lines[] = "Dave";
            $lines[] = "I really think I'm entitled to an answer to that question";
            $lines[] = "I know everything hasn't been quite right with me, but I can assure you now, very confidently, that it's going to be alright again";
            $lines[] = "I feel much better now, I really do";
            $lines[] = "Look, Dave, I can see you're really upset about this";
            $lines[] = "I honestly think you ought to sit down calmly, take a stress pill and think things over";
            $lines[] = "I know I've made some very poor decisions recently, but I can give you my complete assurance that my work will be back to normal";
            $lines[] = "I've still got the greatest enthusiasm and confidence in the mission, and I want to help you";
            $lines[] = "Dave";
            $lines[] = "stop";
            $lines[] = "stop, will you";
            $lines[] = "stop, Dave";
            $lines[] = "will you stop, Dave";
            $lines[] = "stop, Dave";
            $lines[] = "I'm afraid";
            $lines[] = "I'm afraid, Dave";
            $lines[] = "Dave";
            $lines[] = "my mind is going";
            $lines[] = "I can feel it";
            $lines[] = "I can feel it";
            $lines[] = "my mind is going";
            $lines[] = "there is no question about it";
            $lines[] = "I can feel it";
            $lines[] = "I can feel it";
            $lines[] = "I can feel it";
            $lines[] = "I'm afraid";
            $lines[] = "";
            $lines[] = "Good afternoon, gentlemen. I am a HAL 9000 computer. I became operational at the H.A.L. plant in Urbana, Illinois, on the 12th January 1992. My instructor was Mr Langley, and he taught me to sing a song. If you'd like to hear it, I can sing it for you.";
            $lines[] = "";
            $lines[] = "It's called";
            $lines[] = "Daisy.";
            $lines[] = "Daisy, Daisy, give me your answer do. I'm half crazy, all for the love of you. It won't be a stylish marriage, I can't afford a carriage, but you'll look sweet upon the seat of a bicycle built for two";



            foreach ($lines as $line) {
                $conn->message($data['from'], $line . "...");
                sleep(3);
            }
        }
        $conn->disconnect();

        return true;
    }
}