�
    0  {         � 	      !          h�  %        //                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              	         �    ( �   �'   � �    � ��    ` ) E   �'   � ��   ��   � ��   �    � �PRIMARY�tmd�list�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               0                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      InnoDB                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               �                                           7Stores block settings, such as region and visibility...                                                                                                                                                            � �  {R         P    �  � )                                          bid  module  delta  theme  status 	 weight 
 region  custom  visibility  pages  title  cache        H�        !� I` �       !$ I� '      !/  �     1 	 �      
H� �      !+  �     �  �     �  � D  �!� I� �      !�  {     � �bid�module�delta�theme�status�weight�region�custom�visibility�pages�title�cache� Primary Key: Unique block ID.The module from which the block originates; for example, ’user’ for the Who’s Online block, and ’block’ for any custom blocks.Unique ID for block within a module.The theme under which the block settings apply.Block enabled status. (1 = enabled, 0 = disabled)Block weight within region.Theme region within which the block is set.Flag to indicate how users may control visibility of the block. (0 = Users cannot control, 1 = On by default, but can be hidden, 2 = Hidden by default, but can be shown)Flag to indicate how to show blocks on pages. (0 = Show on all pages except listed pages, 1 = Show only on listed pages, 2 = Use custom PHP code to determine visibility)Contents of the "Pages" block; contains either a list of paths on which to include/exclude the block or PHP code, depending on "visibility" setting.Custom title for the block. (Empty string will use block default title, <none> will remove the title, text will cause block to use specified title.)Binary flag to indicate block cache mode. (-2: Custom cache, -1: Do not cache, 1: Cache per role, 2: Cache per user, 4: Cache per page, 8: Block cache global) See DRUPAL_CACHE_* constants in ../includes/common.inc for more detailed information.