�
    0  t          e 	      !        t  h�  &        //                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       �    ( �   �    �) �   � D�     �   �PRIMARY�title�url�queued�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   InnoDB                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 �                                           ,Stores feeds to be parsed by the aggregator.                                                                                                                                                                       �    W         P    �  � )                                          fid  title  url  refresh  checked 	 queued 
 link  description  image  hash  etag  	modified  block       ! I�       !   D  �!       !2       !< 	      !< 
  D  �!J  $ D  �!U  0 D  �! J� <      !< J��      !; 	 �     !5        !1 �fid�title�url�refresh�checked�queued�link�description�image�hash�etag�modified�block� Primary Key: Unique feed ID.Title of the feed.URL to the feed.How often to check for new feed items, in seconds.Last time feed was checked for new items, as Unix timestamp.Time when this feed was queued for refresh, 0 if not queued.The parent website of the feed; comes from the <link> element in the feed.The parent website’s description; comes from the <description> element in the feed.An image representing the feed.Calculated hash of the feed data, used for validating cache.Entity tag HTTP response header, used for validating cache.When the feed was last modified, as a Unix timestamp.Number of items to display in the feed’s block.