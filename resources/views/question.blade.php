<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile {
            text-align: left;
            margin-bottom: 20px;
        }

        .profile-image {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background-color: #e0e0e0;
            object-fit: cover;
            margin-bottom: 5px;
        }

        .question-box {
            margin-bottom: 30px;
        }

        .question-box h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .comments-section {
            margin-bottom: 30px;
        }

        .comment {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .comment-user-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e0e0e0;
            margin-right: 10px;
            object-fit: cover;
        }

        .comment-content p {
            margin-bottom: 5px;
        }

        .app-download {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .app-download p {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .store-buttons img {
            width: 150px;
            margin: 5px;
        }

        .response-box {
            display: flex;
            justify-content: start;
            align-items: center;
            margin-bottom: 20px;
            gap: 10px;
            width: 50%;
        }

        .response {

            text-align: center;
            padding: 20px;
            border-radius: 8px;
            width: 40%;
            min-width: 200px;
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .circular-progress-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin-bottom: 10px;
        }

        .circular-progress {
            width: 100%;
            height: 100%;
            transform: rotate(-90deg);
        }

        .circle-bg {
            fill: none;
            stroke: #eee;
            stroke-width: 2.8;
        }

        .circle {
            fill: none;
            stroke-width: 2.8;
            stroke-linecap: round;
            transition: stroke-dasharray 0.6s ease;
        }

        .response.true .circle {
            stroke: #38B89A;
        }

        .response.false .circle {
            stroke: #FF6B6B;
        }

        .percentage {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            font-weight: bold;
        }

        .label-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .label1,
        .label2 {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            width: 85%;
            border-radius: 10px;
            font-weight: bold;
            padding: 3px;
            box-sizing: border-box;
        }

        .label1 {
            background-color: #38B89A;
            color: white;
        }

        .label2 {
            background-color: #FFFFFF;
            color: red;
            border: 2px solid #ea4335;
        }

        .label1 img,
        .label2 img {
            margin-right: 5px;
        }

        @media (max-width: 768px) {

            .response-box {
                flex-direction: column;
                gap: 10px;
                width: 100%;
            }

            .response {
                width: 100%;
            }

            .label1,
            .label2 {
                width: 50%;
                font-size: 1.6rem;
            }

            .percentage {
                font-size: 20px;
            }

            .comment {
                flex-direction: column;
            }

            .app-download {
                flex-direction: column;
                text-align: center;
            }

            .store-buttons img {
                width: 140px;
                margin: 10px 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="profile">
            <img src="{{ url('public/frontend/media/emptyUser.svg') }}" alt="Profile" class="profile-image">
            <h1>{{ $question->user_detail->name ?? 'Anonymous' }}</h1>
        </div>

        <div class="question-box">
            <h3>Question:</h3>
            <p>{{ $question->question }}</p>
        </div>
        @php
            $totalVotes = $question->totalYesVote + $question->totalNoVote;
            if ($totalVotes == 0) {
                $totalVotes = 1;
            }
            $tureVotes = ($question->totalYesVote / $totalVotes) * 100;
            $falseVotes = ($question->totalNoVote / $totalVotes) * 100;
        @endphp
        <div class="response-box">
            <div class="response true">
                <div class="circular-progress-container">
                    <svg class="circular-progress" viewBox="0 0 36 36">
                        <path class="circle-bg" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="circle" stroke-dasharray="{{ $tureVotes }}, 100" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="percentage">{{ round($tureVotes) }}%</div>
                </div>
                <div class="label-container">
                    <div class="label1">
                        <img src="{{ url('public/frontend/media/circleCheck1.svg') }}" alt="circle"> True
                    </div>
                </div>
            </div>

            <div class="response false">
                <div class="circular-progress-container">
                    <svg class="circular-progress" viewBox="0 0 36 36">
                        <path class="circle-bg" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="circle" stroke-dasharray="{{ $falseVotes }}, 100" d="M18 2.0845
                            a 15.9155 15.9155 0 0 1 0 31.831
                            a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <div class="percentage">{{ round($falseVotes) }}%</div>
                </div>
                <div class="label-container">
                    <div class="label2">
                        <img src="{{ url('public/frontend/media/close.svg') }}" alt="circle"> False
                    </div>
                </div>
            </div>
        </div>

        <div class="comments-section">
            <h2 style="margin-bottom: 10px;">Comments</h2>
            <div style="background-color: #E7ECE980; padding:10px;border-radius:10px;">

                @if ($question->comments->isEmpty())
                    <p>No comments added yet.</p>
                @else
                    @foreach ($question->comments->take(3) as $item)
                        <div style="margin-bottom: 10px;">
                            <div style="display: flex; align-items: center;">
                                <div>
                                    <img src="{{ $item->user_detail && $item->user_detail->image
                                        ? asset('public/storage/' . $item->user_detail->image)
                                        : url('public/frontend/media/emptyUser.svg') }}"
                                        alt="User" class="comment-user-image">
                                </div>
                                <div
                                    style="display: flex; flex-direction: column; align-items: start; margin-left: 5px;">
                                    <p><strong>{{ $item->user_detail->name ?? 'Anonymous' }}</strong></p>
                                    <p><span>{{ $item->created_at->diffForHumans() }}</span></p>
                                </div>
                            </div>
                            <div style="margin-top: 10px;">
                                <p>{{ $item->comment }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <hr>
        <div class="app-download">
            <div>
                <p>Download The App</p>
            </div>
            <div class="store-buttons">
                <a href="https://apps.apple.com/us/app/my-mufti/id6446103667">
                    <img src="{{ url('public/frontend/media/app.svg') }}" alt="App Store">
                </a>
                <a href="https://play.google.com/store/apps/details?id=com.mlbranch.mymufti&hl=en&gl=US">
                    <img src="{{ url('public/frontend/media/google.svg') }}" alt="Google Play">
                </a>
            </div>
        </div>
    </div>
</body>

</html>
