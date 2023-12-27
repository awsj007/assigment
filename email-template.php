<?php 

function assigment_external_api(){
    $per_page = 3;
    $page = isset($_GET['_start']) ? $_GET['_start'] : 0;
    $api_endpoint = 'https://jsonplaceholder.typicode.com/posts?_start=' . $page . '&_limit=' . $per_page; //
    $response = wp_remote_get($api_endpoint);
        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = wp_remote_retrieve_body($response);
            $posts = json_decode($data, true);
        if (!empty($posts)) {
            foreach ($posts as $post) {?>

<div class="container mx-auto shadow-lg m-4 p-4 w-[1024px]">
    <div class="flex gap-2 justify-start align-middle p-2">
        <span
            class="text-sm font-bold text-white bg-blue-300 rounded w-10 h-10 flex justify-center align-middle p-2"><?php echo esc_html($post['id']) ?></span>
        <h1 class="text-lg font-bold"><?php echo esc_html($post['title']) ?></h1>
    </div>
    <p class="text-sm my-3"><?php echo esc_html($post['body']) ?></p>

</div>

<?php
    }
} else {
    echo 'No posts found.';
}
} else {
echo 'Error fetching data from the API.';
}
}
?>