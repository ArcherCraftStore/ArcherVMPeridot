�
    0  ��          8 	      !        �  h�          //                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       �  @ ) �    �   @� �PRIMARY�token�                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           �                                                                                                                                                                                                                        InnoDB                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             J                                           6Stores details about batches (processes that run in...                                                                                                                                                             V �   �          P   t  V )                                          bid  token  
timestamp  batch 

   @    I�    @   !� 
 �  @   p  �  �  �?@ �bid�token�timestamp�batch� Primary Key: Unique batch ID.A string token generated against the current user’s session id and the batch id, used to ensure that only the user who submitted the batch can effectively access it.A Unix timestamp indicating when this batch was submitted for processing. Stale batches are purged at cron time.A serialized array containing the processing data for the batch.