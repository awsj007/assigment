<?php
/*
Plugin Name: External API
Plugin URI: https://jsonplaceholder.typicode.com/posts
Description: Simple plugin that get data from External api
Version: 1.0
Author URI: #
*/

defined('ABSPATH') || die ('Unauthorized access');

add_shortcode( 'external_data', 'assigment_external_api' );

function assigment_external_api(){
    $per_page = 10;
    $page = isset($_GET['_start']) ? $_GET['_start'] : 0;
    $api_endpoint = 'https://jsonplaceholder.typicode.com/posts?_start=' . $page . '&_limit=' . $per_page; // 
    $response = wp_remote_get($api_endpoint);
    if (!is_wp_error($response) && $response['response']['code'] === 200) {
        $data = wp_remote_retrieve_body($response);
        $posts = json_decode($data, true);
    
        if (!empty($posts)) {
            foreach ($posts as $post) {
?>
<div class="container mx-auto shadow-lg m-2 p-4">
    <span class="text-sm font-bold"><?php echo esc_html($post['id']) ?></span>
    <h1 class="text-lg font-bold"><?php echo esc_html($post['title']) ?></h1>
    <p class="text-sm my-3"><?php echo esc_html($post['body']) ?></p>

    <button class="bg-blue-500 px-5 py-2 text-white font-bold transition" onclick="openModal('modelConfirm')">
        Subscribe Now
    </button>
</div>

<!-- Subscribe Now Popup start -->




<div id="modelConfirm" class="fixed hidden z-50 inset-0 bg-white bg-opacity-60  overflow-y-auto w-full h-full px-4 ">
    <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">

        <div class="flex justify-end p-2">
            <button onclick="closeModal('modelConfirm')" type="button"
                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">

            </button>
        </div>
        <form class="myform" action="" method="post">
            <div class="p-6 pt-0 text-left">
                <div class="flex flex-wrap -m-2">
                    <div class="p-2 w-full">
                        <div class="relative">
                            <label for="firstname" class="leading-7 text-sm text-gray-600">First
                                Name</label>
                            <input type="text" id="firstname" name="firstname"
                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>
                    <div class="p-2 w-full">
                        <div class="relative">
                            <label for="lastname" class="leading-7 text-sm text-gray-600">Last
                                Name</label>
                            <input type="text" id="lastname" name="lastname"
                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>
                    <div class="p-2 w-full">
                        <div class="relative">
                            <label for="email" class="leading-7 text-sm text-gray-600">Email</label>
                            <input type="email" id="email" name="email" isEmail required
                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>
                    </div>
                    <div class="p-2 w-full">
                        <div class="relative">
                            <label for="phone" class="leading-7 text-sm text-gray-600">Phone
                                Number</label>
                            <input type="tel" id="phone" name="phone" required isPhone
                                class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                        </div>

                    </div>


                </div>
                <input type="submit" name="submit" value="Submit"
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2"
                    Subscribe Now>
                <a href="#" onclick="closeModal('modelConfirm')"
                    class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center"
                    data-modal-toggle="delete-user-modal">
                    No, cancel
                </a>
            </div>
        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
    integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    $('.myform').on('submit', function() {

        // Add text 'loading...' right after clicking on the submit button. 
        $('.output_message').text('Loading...');

        var form = $(this);
        $.ajax({
            url: "email-template.php",
            method: form.attr('method'),
            data: form.serialize(),
            success: function(result) {
                if (result == 'success') {
                    $('.output_message').text('Message Sent!');
                } else {
                    $('.output_message').text('Error Sending email!');
                }
            }
        });

        // Prevents default submission of the form after clicking on the submit button. 
        return false;
    });
});
</script>


<script type="text/javascript">
window.openModal = function(modalId) {
    document.getElementById(modalId).style.display = 'block'
    document.getElementsByTagName('body')[0].classList.add('overflow-y-hidden')
}

window.closeModal = function(modalId) {
    document.getElementById(modalId).style.display = 'none'
    document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
}

// Close all modals when press ESC
document.onkeydown = function(event) {
    event = event || window.event;
    if (event.keyCode === 27) {
        document.getElementsByTagName('body')[0].classList.remove('overflow-y-hidden')
        let modals = document.getElementsByClassName('modal');
        Array.prototype.slice.call(modals).forEach(i => {
            i.style.display = 'none'
        })
    }
};
</script>
<!-- Subscribe Now Popup End -->
<?php
            }
            $next_page = $per_page + 10;
            echo '<button  class="bg-blue-500 px-5 py-2 text-white font-bold" id="load-more-btn" data-next-page="' . $next_page . '">Load More</button>';
        } else {
            echo 'No posts found.';
        }
    } else {
        echo 'Error fetching data from the API.';
    }

}

?>

<!-- <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
    integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
<script>
document.getElementById('load-more-btn').addEventListener('click', function() {
    const nextPage = this.getAttribute('data-next-page');
    const endpoint = 'https://jsonplaceholder.typicode.com/posts?_start=' + nextPage + '&_limit=' + $per_page;

    fetch(endpoint)
        .then(response => response.json())
        .then(data => {

            if (data.length > 0) {
                data.forEach(post => {
                    const postContainer = document.createElement('div');
                    postContainer.classList.add('post');
                    postContainer.innerHTML = '<h2>' + post.title + '</h2>' +
                        '<p>' + post.body + '</p>';
                    document.getElementById('posts-container').appendChild(postContainer);
                });
                const nextPageNum = nextPage + 1;
                document.getElementById('load-more-btn').setAttribute('data-next-page', nextPageNum);
            } else {
                this.style.display = 'none'; // Hide the "Load More" button
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
});
</script> -->

<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                clifford: '#da373d',
            }
        }
    }
}
</script>