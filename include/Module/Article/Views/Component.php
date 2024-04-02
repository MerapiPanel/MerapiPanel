<?php

namespace MerapiPanel\Module\Article\Views;

use MerapiPanel\Core\View\Component\ProcessingComponent;

class Component
{


    /**
     * @label Content list
     * @media <i style='font-size: 2rem; padding: 1rem' class='fa-solid fa-bars-staggered'></i>
     * @category Content
     * @return string
     */
    public final function ListPost($order = [
        'DESC',
        'ASC'
    ], $limit = 10)
    {

        // handle mook view request
        if (ProcessingComponent::isMookRender()) {

            $content = "<ul>";
            for ($i = 0; $i < ($limit < 3 ? 3 : ($limit > 10 ? 10 : $limit)); $i++) {
                $x = $order == 'DESC' ? $limit - $i : $i + 1;
                $content .= "<li><a href='#'>Mook Content " .  $x . "</a></li>";
            }
            $content .= "</ul>";

            return $content;
        }


        $content = "<ul>";
        for ($i = 0; $i < ($limit < 3 ? 3 : ($limit > 10 ? 10 : $limit)); $i++) {
            $content .= "<li><a href='#'>Real Content " .  $i + 1 . "</a></li>";
        }
        $content .= "</ul>";

        return $content;
    }



    /**
     * @label Content Title
     * @category Content
     * @return string
     */
    public final function Title()
    {

        if(ProcessingComponent::isMookRender()){
            return "<h1>Mook Title</h1>";
        }

        return "<h1>Content Not Found's</h1>";
    }


    /**
     * @label Content Article
     * @category Content
     * @arg order | true | The number of the article in the content listing.
     * @arg limit | false | How many articles should be shown? Default is 3
     * 
     */
    public final function Article()
    {

        return "<div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Morbi non arcu risus quis varius quam quisque id. Sodales neque sodales ut etiam sit amet nisl. Fermentum et sollicitudin ac orci phasellus egestas tellus. Sed enim ut sem viverra. Neque vitae tempus quam pellentesque nec nam aliquam. Molestie a iaculis at erat pellentesque adipiscing commodo elit at. Viverra maecenas accumsan lacus vel. Vel risus commodo viverra maecenas accumsan lacus vel facilisis volutpat. Odio morbi quis commodo odio aenean sed adipiscing diam. Dui nunc mattis enim ut tellus elementum sagittis vitae. Ornare aenean euismod elementum nisi quis. Donec ultrices tincidunt arcu non. Lobortis feugiat vivamus at augue eget arcu dictum varius duis. Ullamcorper sit amet risus nullam eget felis eget nunc lobortis. Consectetur lorem donec massa sapien faucibus.</p><p>Vitae semper quis lectus nulla at volutpat. Velit egestas dui id ornare arcu odio ut. Commodo sed egestas egestas fringilla phasellus faucibus. Posuere ac ut consequat semper viverra nam. Aenean pharetra magna ac placerat vestibulum lectus mauris. Tristique senectus et netus et. Porta non pulvinar neque laoreet suspendisse. Posuere urna nec tincidunt praesent semper feugiat nibh. Quisque non tellus orci ac auctor. Purus in massa tempor nec feugiat. Dictum sit amet justo donec enim diam vulputate ut pharetra. Nulla aliquet enim tortor at auctor urna nunc id cursus. Urna molestie at elementum eu facilisis. Pellentesque sit amet porttitor eget dolor morbi non arcu risus. Aenean et tortor at risus viverra adipiscing. Cras adipiscing enim eu turpis egestas. Ultrices neque ornare aenean euismod.</p><p>Lectus nulla at volutpat diam ut venenatis tellus in. Erat velit scelerisque in dictum non consectetur. Sed euismod nisi porta lorem mollis aliquam ut porttitor leo. Massa id neque aliquam vestibulum morbi blandit cursus risus at. Nunc mi ipsum faucibus vitae aliquet. Nunc sed augue lacus viverra vitae congue eu consequat ac. Leo vel orci porta non pulvinar neque. Netus et malesuada fames ac. Et leo duis ut diam quam nulla porttitor massa id. Sagittis nisl rhoncus mattis rhoncus urna neque viverra. In vitae turpis massa sed elementum tempus egestas. Quis lectus nulla at volutpat diam ut venenatis tellus in.</p></div>";
    }
}
